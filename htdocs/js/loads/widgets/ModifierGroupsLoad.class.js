NGS.createLoad("crm.loads.widgets.modifier_groups", {

	getContainer : function() {
		return "modifierGroupsWrapper";
	},


	onError : function(params) {

	},

	afterLoad : function() {
        console.log("After Load block for Modifiers");
        $( ".draggable" ).draggable({
            connectToSortable: ".connectedSortable",
            handle : ".move",
            helper: "clone",
            revert: "invalid"
        }).disableSelection();


	}
});
