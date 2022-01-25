context('Footer', () => {
	before(() => {
		cy.visit('http://localhost:3000/pattern-library')
	});

	it('exists', () => {
		cy.get('.footer')
			.should('exist')
			.and('be.visible');
	});

	it('renders a logo', () => {
		cy.get('.footer .footer__logo img')
			.should('exist')
			.and('be.visible');
	});

	it('has some legal blurb', () => {
		cy.get('.footer .footer__text')
			.should('exist')
			.and('be.visible')
			.and('not.be.empty');
	});

	it('has a navigation', () => {
		cy.get('.footer .footer__nav')
			.should('exist')
			.and('be.visible')
			.and('have.length', 3)
			.each((nav) => {
				cy.wrap(nav)
					.find('a')
					.should('exist')
					.and('be.visible')
					.and('not.be.empty')
					.and('have.length', 3)
					.and('have.attr', 'href')

		});
	});

});