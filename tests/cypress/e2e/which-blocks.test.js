describe('Basic tests', () => {
	beforeEach(() => {
		cy.login();
	});

	it('Make sure the plugin is active', () => {
		cy.deactivatePlugin('which-blocks');
		cy.activatePlugin('which-blocks');
	});

	it('Should see menu link', () => {
		cy.visit('/wp-admin/tools.php');
		cy.get('#menu-tools li a').contains('Which Blocks');
	});

	it('Should read blocks usage', () => {
		cy.visit('/wp-admin/tools.php?page=which-blocks');

		const expected = {
			'core/paragraph': 3,
			'core/heading': 2,
			'core/quote': 1,
			'core/buttons': 1,
			'core/list': 1,
			'core/button': 1,
		};

		for (const [key, value] of Object.entries(expected)) {
			cy.get('td.name')
				.contains(new RegExp(key))
				.closest('tr')
				.find('.usage')
				.should('contain.text', value);
		}
	});
});
