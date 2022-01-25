import LazyLoad from 'vanilla-lazyload';
import '../polyfills/**/*';
import { header, toggler, filters, postsArchive } from '../modules';

export default {
	// JavaScript to be fired on all pages
	init() {
		new LazyLoad();
		header();
		toggler();
		filters();
		postsArchive();
	},

	// JavaScript to be fired on all pages, after page specific JS is fired
	finalize() {

		/**
		Scroll to top
		**/

		var scrollToTopBtn = document.getElementById('scrollToTop')
		var rootElement = document.documentElement

		function scrollToTop() {
			rootElement.scrollTo({
				top: 0,
				behavior: 'smooth',
			})
		}
		scrollToTopBtn.addEventListener('click', scrollToTop)

		/**
		Wrap wysiwyg blocks - the following adds classes to the first and last wp-core-block items so that multiple siblings can be styled as a block
		**/

		var wysiwygDivs = Array.from(document.querySelectorAll('.wp-core-block'));
		var wysiwygFirstItems = [];
		var wysiwygLastItems = [];

		wysiwygDivs.forEach(div => !div.previousElementSibling.classList.contains('wp-core-block') ? wysiwygFirstItems.push(div) : null)
		wysiwygFirstItems.forEach(div => div.classList.add('wp-core-block__first-item'));


		wysiwygDivs.forEach(div => div.nextElementSibling === null || !div.nextElementSibling.classList.contains('wp-core-block') ? wysiwygLastItems.push(div) : null)
		wysiwygLastItems.forEach(div => div.classList.add('wp-core-block__last-item'));

		/**
		*	Add class to wysiwyg block image
		**/

		var wysiwygFigs = Array.from(document.querySelectorAll('.wysiwyg__content figure img'));
		var wysiwygImgs = Array.from(document.querySelectorAll('.wysiwyg__content p img'));

		// for p tags containing images
		wysiwygImgs.forEach(img => {
			let imageContainer = img.parentElement;
			let classArray = Array.from(img.classList);
			let alignment = classArray[classArray.length - 1];
			imageContainer.removeAttribute('class');
			imageContainer.classList.add('wysiwyg__image-container')
			imageContainer.classList.add(`img--${alignment}`)
		})
		// for figure tags containing images
		wysiwygFigs.forEach(img => {
			let imageContainer = img.parentElement;
			imageContainer.removeAttribute('class');
			imageContainer.classList.add('wysiwyg__image-container');
			imageContainer.removeAttribute('style');
		})

		/**
		*	Remove empty p tags from WYSIWYG
		**/

		var wysiwygParas = Array.from(document.querySelectorAll('.wysiwyg__content p'));
		var wysiwygEmptyParas = wysiwygParas.filter(para => para.innerHTML === '&nbsp;')
		wysiwygEmptyParas.forEach(para => para.remove())


		/**
		*	Add custom class to siblings of the half-width-textarea
		**/

		const siblings = (elem) => {
			let siblings = [];

			// if no parent, return empty list
			if (!elem.parentNode) {
					return siblings;
			}
			let sibling = elem.parentNode.firstElementChild;

			do {
				if (sibling != elem) {
						siblings.push(sibling);
				}
			} while (sibling = sibling.nextElementSibling);
			siblings.forEach(sibling => sibling.classList.add('half-width-field'))
		};
		const textareaContainers = document.querySelectorAll('.half-width-textarea');
		textareaContainers.forEach(container => siblings(container))
	},
};
