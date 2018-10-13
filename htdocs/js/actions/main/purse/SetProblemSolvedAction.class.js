NGS.createAction("crm.actions.main.purse.set_problem_solved", {
    onError: function (res) {
    },
    afterAction: function (res) {
        $('#problem_solved_' + res.id).closest('tr').remove();
    }
});
