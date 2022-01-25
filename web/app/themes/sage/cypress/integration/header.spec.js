context('Header', () => {
	before(() => {
		cy.visit('http://localhost:3000/pattern-library')
	});

	describe('Mobile Header', () => {
		beforeEach(() => {
			cy.viewport('iphone-5');
		});

		it('exist', () => {
			cy.get('.header')
				.should('exist')
				.and('be.visible');
		});

		it('has a menu toggle button', () => {
			cy.get('.header__toggle')
				.should('exist')
				.and('be.visible');
		});

		it('has nav items which are not visible', () => {
			cy.get('.header__item')
				.should('exist')
				.and('not.be.visible');
		});

		it('toggles the items when clicking the toggle', () => {
			cy.get('.header__toggle')
				.as('toggle')
				.click();

			cy.get('.header__item')
				.and('be.visible');

			cy.get('@toggle')
				.click();

			cy.get('.header__item')
				.and('not.be.visible');
		});

		it('has aria attributes to show that the menu is toggled or not', () => {
			cy.get('.header__toggle')
				.as('toggle')
				.should('have.attr', 'aria-expanded', 'false')
				.click()
				.should('have.attr', 'aria-expanded', 'true')
				.click()
				.should('have.attr', 'aria-expanded', 'false');
		});

		it('can have sub menus with a button to expand them', () => {
			cy.get('.header__toggle')
				.as('toggle')
				.click();

			cy.get('.header__subtoggle__button')
				.should('exist')
				.and('be.visible');

			cy.get('@toggle')
				.click();
		});

		it('toggles a submenu when clicking the expander button', () => {
			cy.get('.header__toggle')
				.as('toggle')
				.click();

			cy.get('.header__subtoggle__button')
				.click();

			cy.get('.header__submenu--visible')
				.should('exist')
				.and('be.visible');

			cy.get('.header__subtoggle__button')
				.click()

			cy.get('.header__submenu--visible')
				.should('not.exist');

			cy.get('.header__submenu')
				.should('exist')
				.and('not.be.visible');

			cy.get('@toggle')
				.click();
		});

		it('has aria attributes to show that the submenu is toggled or not', () => {
			cy.get('.header__toggle')
				.as('toggle')
				.click();

			cy.get('.header__subtoggle__button')
				.should('have.attr', 'aria-expanded', 'false')
				.click()
				.should('have.attr', 'aria-expanded', 'true')
				.click()
				.should('have.attr', 'aria-expanded', 'false');

			cy.get('@toggle')
				.click();
		});

	});
});