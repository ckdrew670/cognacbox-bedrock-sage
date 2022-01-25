context('Image', () => {
	before(() => {
		cy.visit('http://localhost:3000/pattern-library')
	});

	it('should accept multiple source elements', () => {
		cy.get('.pattern-library__example .image')
			.last()
			.find('source')
			.should('have.length', 2)
	});

	it('should work with lazy loading', () => {
		cy.get('.pattern-library__example .image')
			.first()
			.should('exist')
			.find('img')
			.should('have.attr', 'data-ll-status', 'loaded')
			.and('have.class', 'lazy')
			.and('be.visible')
	});

	it('should work without lazy loading', () => {
		cy.get('.pattern-library__example .image')
		.last()
		.should('exist')
		.find('img')
		.should('not.have.attr', 'data-ll-status', 'loaded')
		.and('be.visible')
	});

});