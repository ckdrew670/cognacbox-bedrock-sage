

import logger from './logger';

class DataValidator {
	/**
	 * Sets class properties and validates the data
	 *
	 * @param  {object} data
	 * @param  {object} options
	 * @return {DataValidator}
	 */
	constructor(data, options) {
		this.valid = true;
		this.data = data;
		this.options = options;

		this.validate();
	}

	/**
	 * Iterates over each key in the this.options.rules object, which will
	 * return the rule name. Uses the rule name to access the rules object
	 * value, which is an array of field names. Iterates over the field names,
	 * calling the rule name as a method and passing in the field.
	 *
	 * passing in the
	 * @return {boolean}
	 */
	validate() {
		Object.keys(this.options.rules).forEach(rule => {
			const fields = this.options.rules[rule];

			fields.forEach(field => {
				this[rule](field);
			});
		});
	}

	/**
	 * Accesses the value to be evaluated using the field variable as a key.
	 * Sets this.valid to false if the field is not present or truthy in the
	 * this.data object
	 *
	 * @param  {string} field
	 * @return {void}
	 */
	required(field) {
		if (!this.data[field]) {
			this.valid = false;

			logger(
				`${this.callingClass  }: the ${  field  } is required but not given`,
				'error'
			);
		}
	}

	/**
	 * Accesses the value to be evaluated using the field variable as a key.
	 * Sets this.valid to false if an element with the value as a selector is
	 * not on the DOM
	 *
	 * @param  {string} field
	 * @return {void}
	 */
	onPage(field) {
		if (!document.querySelector(this.data[field])) {
			this.valid = false;

			logger(
				`${this.options.context
				}: the ${
					field
				} you passed in should be on the DOM but is not`,
				'error'
			);
		}
	}

	/**
	 * Accesses the value to be evaluated using the field variable as a key.
	 * Sets this.valid to false if an element with the value as a selector is
	 * not on the DOM
	 *
	 * @param  {string} field
	 * @return {void}
	 */
	callable(field) {
		if (this.data[field] && typeof this.data[field] !== 'function') {
			this.valid = false;

			logger(
				`${this.options.context
				}: the '${
					field
				}' you passed in should be a function`,
				'error'
			);
		}
	}
}

export default DataValidator;
