

import axios from 'axios';
import logger from './logger';
import EventBus from './eventBus';
import Pipeline from './pipeline';
import DataValidator from './dataValidator';

class ContentLoader {
	/**
	 * Sets options based on defaults and those passed in and validates the
	 * resulting object against what is needed. If not valid, triggers won't be
	 * registered so the content will not load in in any circumstances.
	 *
	 * @param  {object} options
	 * @return {void}
	 */
	constructor(options) {
		const defaults = this.getDefaults();

		this.options = Object.assign(defaults, options);
		const validator = new DataValidator(this.options, {
			context: this.constructor.name,
			rules: {
				required: [
					'route',
					'endpoint',
					'params',
					'nonce',
					'container',
					'containerParent',
					'loadingError',
					'emptyError',
					'triggers',
					'activeClass',
					'loadingClass',
				],
				onPage: ['container'],
			},
		});

		this.parent = document.querySelector(this.options.containerParent);

		this.container = this.parent.querySelector(this.options.container);
		this.paginationTrigger = this.parent.querySelector(
			this.options.paginationTrigger
		);

		if (validator.valid) {
			this.addTriggers();

			if (this.paginationTrigger) {
				this.addPagination();
			}
		}
	}

	/**
	 * Adds event listeners for the configured triggers to load content
	 *
	 * @return {void}
	 */
	addTriggers() {
		this.options.triggers.forEach(trigger => {
			const event = Object.keys(trigger)[0];

			if (event == 'load') {
				return this.loadContent();
			}

			this.parent.querySelectorAll(trigger[event]).forEach(element => {
				element.addEventListener(event, e => {
					e.preventDefault();

					this.setParams(event, element);

					this.setTriggerActive(e.target, trigger[event]);
					this.loadContent();
				});
			});
		});
	}

	/**
	 * Adds event listeners for the pagination trigger to load more content
	 *
	 * @return {void}
	 */
	addPagination() {
		this.paginationText = this.paginationTrigger.innerHTML;

		this.paginationTrigger.addEventListener(
			'click',
			function(e) {
				e.preventDefault();

				this.setParams('click', this.paginationTrigger);
				this.setTriggerActive(this.paginationTrigger);
				this.loadMoreContent();
			}.bind(this)
		);
	}

	/**
	 * Fires a 'content-loading' event for developers to hook into.
	 *
	 * @return {void}
	 */
	fireLoadingEvent() {
		EventBus.publish('content-loader.loading', this.options.action);
	}

	/**
	 * Fires a 'content-loaded' event for developers to hook into.
	 *
	 * @return {void}
	 */
	fireLoadedEvent() {
		EventBus.publish('content-loader.loaded', this.options.action);
	}

	/**
	 * Sets parameters for the request body depending on the event type. If it
	 * is a submit event, it pulls them from form fields. If it is a click event
	 * it will pull params from any data attribute starting with `data-param-`
	 *
	 * @param {Event} event
	 * @param {node} element
	 */
	setParams(event, element) {
		this.options.params.paged = this.options.currentPage + 1;

		if (event == 'click') {
			for (const dataItem in element.dataset) {
				const parts = dataItem.split(/(?=[A-Z])/);
				if (parts[0] == 'param') {
					this.options.params[parts[1].toLowerCase()] =
						element.dataset[dataItem];
				}
			}
		}

		if (event == 'submit') {
			[...element.querySelectorAll('input , select')].forEach(input => {
				this.options.params[input.name] = input.value;
			});
		}
	}

	/**
	 * Returns container to its original state
	 *
	 * @return {void}
	 */
	resetContainer() {
		this.options.currentPage = 0;
	}

	/**
	 * Sets the container loading classm fetches data and sticks it in the
	 * container. Then fires 'content-loaded' event and removes loading class
	 * from container
	 *
	 * @return {void}
	 */
	loadContent() {
		if (!this.loading) {
			new Pipeline()
				.then(() => (this.loading = true))
				.then(() => this.resetContainer())
				.then(() => this.setPaginationHTML())
				.then(() => this.setContainerLoading())
				.then(() => this.fireLoadingEvent())
				.then(() => new Promise(resolve => setTimeout(() => resolve(), 1000)))
				.then(() => this.fetchContent())
				.then(response => this.replaceContent(response))
				.then(() => this.fireLoadedEvent())
				.then(() => this.unsetContainerLoading())
				.then(() => (this.loading = false))
				.then(() => this.setPaginationHTML())
				.catch(error => this.handleError(error));
		}
	}

	/**
	 * Sets the container loading classm fetches data and sticks it in the
	 * container. Then fires 'content-loaded' event and removes loading class
	 * from container
	 *
	 * @return {void}
	 */
	loadMoreContent() {
		if (!this.loading) {
			new Pipeline()
				.then(() => (this.loading = true))
				.then(() => this.setPaginationHTML())
				.then(() => this.fireLoadingEvent())
				.then(() => new Promise(resolve => setTimeout(() => resolve(), 1000)))
				.then(() => this.fetchContent())
				.then(response => this.appendContent(response))
				.then(() => this.fireLoadedEvent())
				.then(() => (this.loading = false))
				.then(() => this.setPaginationHTML())
				.catch(error => this.handleError(error));
		}
	}

	/**
	 * Sends a request to the specified endpoint with any given params
	 *
	 * @return {Promise}
	 */
	 fetchContent() {
		const data = Object.assign(
			{ nonce: this.options.nonce },
			this.options.params
		);


		try {
			return axios({
				method: 'get',
				url: `${this.options.endpoint}${this.options.route}`,
				params: data,
			});
		} catch (error) {
			return this.handleError(error);
		}
	}

	/**
	 * Replaces container content with response HTML
	 *
	 * @param  {Promise} response
	 * @return {void}
	 */
	replaceContent(response) {
		this.handleContent(
			response,
			response => {
				this.container.innerHTML = response.data.views;
				this.options.currentPage = 1;
			},
			() => {
				this.container.innerHTML = this.options.emptyError;
			}
		);

		return true;
	}

	/**
	 * Appends response HTM to content container
	 *
	 * @param  {Promise} response
	 * @return {void}
	 */
	appendContent(response) {
		this.handleContent(response, response => {
			this.container.innerHTML += response.data.views;
			this.options.currentPage++;
		});

		return true;
	}

	/**
	 * Populates the given container with the data passed in (usually a template
	 * served by the Ajax endpoint)
	 *
	 * @param  {string} data
	 * @param  {callable} callback
	 * @return {void}
	 */
	handleContent(response, successCallback, emptyCallback) {
		if (response && response.status === 200) {
			successCallback.call(this, response);
		} else if (
			response &&
			response.status !== 200
		) {
			if (this.paginationTrigger) {
				this.paginationTrigger.innerHTML = 'No more posts';
			}

			if (emptyCallback) {
				emptyCallback.call(this);
			}
		} else {
			this.handleError(response.data.data.message);
		}
	}

	/**
	 * Sets the loading class on the container
	 *
	 * @return {void}
	 */
	setContainerLoading() {
		this.container.classList.add(this.options.loadingClass);
		this.container.innerHTML =
			this.options.loadingContent + this.container.innerHTML;
	}

	/**
	 * Removes the loading class from the container
	 *
	 * @return {void}
	 */
	unsetContainerLoading() {
		this.container.classList.remove(this.options.loadingClass);
	}

	/**
	 * Removes the loading status from the trigger
	 *
	 * @return {void}
	 */
	setPaginationHTML() {
		if (this.paginationTrigger) {
			this.paginationTrigger.classList[this.loading ? 'add' : 'remove'](['hidden', 'btn--disabled']);
			this.paginationTrigger.setAttribute('aria-hidden', this.loading);
			this.paginationTrigger.setAttribute('aria-disabled', this.loading);
			this.paginationTrigger.innerHTML = this.loading ? 'Loading...' : this.paginationText;
		}
	}

	/**
	 * Sets the loading class on the container
	 *
	 * @return {void}
	 */
	setTriggerActive(addTo, removeFrom) {
		if (removeFrom) {
			[...this.parent.querySelectorAll(removeFrom)].forEach(trigger => {
				trigger.classList.remove(this.options.activeClass);
				trigger.setAttribute('aria-current', false);
			});
		}

		addTo.classList.add(this.options.activeClass);
		addTo.setAttribute('aria-current', true);
	}

	handleError(error) {
		if (error) {
			logger(error, 'error');
		}

		this.container.innerHTML = this.options.emptyError;
	}

	/**
	 * Specifies the default options for this class
	 *
	 * Options -
	 *     action:              ajax action defined in your Ajax class
	 *     endpoint:            Wordpress ajax endpoint file
	 *     params:              extra params to send in request body
	 *     nonce:               the nonce to be used to validate the request
	 *     container:           the selector for the element to put content in
	 *     containerParent:     the selector for the element containing the
	 *                          container and all triggers
	 *     paginationTrigger:   element to trigger pagination
	 *     loadingError:        error to be displayed to user on failure
	 *     emptyError:          error to be displayed to user on empty response
	 *     triggers:            an array of objects defining when to load content
	 *                          [{ JS_EVENT_NAME: SELECTOR_TO_ATTACH_EVENT_TO }]
	 *     activeClass:         class to apply to the active trigger
	 *     loadingClass:        class to apply to the container when loading content
	 *
	 * @return {array}
	 */
	getDefaults() {
		return {
			currentPage: 0,
			endpoint: `${LOCALISED_VARS.siteUrl}/index.php/wp-json/v1/`,
			params: {},
			nonce: LOCALISED_VARS.ajaxnonce,
			container: '.js-content-loader-container',
			containerParent: '.js-content-loader-container-parent',

			paginationTrigger: '.js-content-loader-next-page-trigger',

			loadingError:
				'<p class="error">Unfortunately, there was an error loading the additional posts. Please try again.</p>',
			emptyError:
				'<p class="container error">There is no content to display.</p>',

			triggers: [],

			activeClass: 'is-active',
			loadingClass: 'is-loading',
			loadingContent:
				'<div class="cssload-container band band--double band--no-top"><div class="cssload-speeding-wheel"></div></div>',
		};
	}
}

export default ContentLoader;
