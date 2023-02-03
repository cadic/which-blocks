describe("Basic tests", () => {
  before(() => {
    cy.login();
  });

  it("Make sure the plugin is active", () => {
    cy.deactivatePlugin("which-blocks");
    cy.activatePlugin("which-blocks");
  });

  it("Should see menu link", () => {
    cy.visit("/wp-admin/tools.php");
    cy.get("#menu-tools li a").contains("Which Blocks");
  });

  it("Should read blocks usage", () => {
    cy.visit("/wp-admin/tools.php?page=which-blocks");

    const expected = {
      "Paragraph, core/paragraph": 3,
      "Heading, core/heading": 2,
      "Quote, core/quote": 1,
      "Buttons, core/buttons": 1,
      "List, core/list": 1,
      "Button, core/button": 1,
      "List item, core/list-item": 1,
    };

    for (const [key, value] of Object.entries(expected)) {
      cy.get("td.name")
        .contains(key)
        .closest("tr")
        .find(".usage")
        .should("contain.text", value);
    }
  });
});
