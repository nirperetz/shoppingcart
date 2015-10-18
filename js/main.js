var AppRouter = Backbone.Router.extend({

    routes: {
        ""                  : "list",
        "cart/page/:page"	: "list",
        "cart/add"         : "addWine",
        "cart/:id"         : "wineDetails",
        "about"             : "about",
		"payment"           : "about"  
    },

    initialize: function () {
        this.headerView = new HeaderView();
        $('.header').html(this.headerView.el);
    },

	list: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var cart = new Cart();
        cart.fetch({success: function(){
            $("#content").html(new WineListView({model: cart}).el);
        }});
       // this.headerView.selectMenuItem('home-menu');
    },

    wineDetails: function (id) {
        var wine = new Product({id: id});
        wine.fetch({success: function(){
            $("#content").html(new WineView({model: wine}).el);
        }});
        this.headerView.selectMenuItem();
    },

	addWine: function() {
        var wine = new Product();
        $('#content').html(new WineView({model: wine}).el);
        this.headerView.selectMenuItem('add-menu');
	},

    about: function () {
        if (!this.aboutView) {
            this.aboutView = new AboutView();
        }
        $('#content').html(this.aboutView.el);
        this.headerView.selectMenuItem('about-menu');
    }

});

utils.loadTemplate(['HeaderView', 'WineView', 'WineListItemView', 'AboutView'], function() {
    app = new AppRouter();
    Backbone.history.start();
});