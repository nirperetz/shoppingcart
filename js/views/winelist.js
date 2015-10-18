window.WineListView = Backbone.View.extend({

    initialize: function () {
		this.model.on('change', this.render, this);
        this.render();
    },

    render: function () {
        var cart = this.model.models;
        var len = cart.length;
      //  var startPos = (this.options.page - 1) * 8;
    //    var endPos = Math.min(startPos + 8, len);
		var total = 0;
		var totalprice = 0;
        $(this.el).html('<ul class="thumbnails"></ul>');
        for (var i = 0; i < len; i++) {
			total += parseInt(cart[i].get('added'));
			totalprice += parseInt(cart[i].get('added'))*cart[i].get('price');
			console.log(total);
            $('.thumbnails', this.el).append(new WineListItemView({model: cart[i]}).render().el);
        }
		//update total
		
			 $('#total').html(total);
			 $('#basket').html(totalprice);
      //  $(this.el).append(new Paginator({model: this.model, page: this.options.page}).render().el);

        return this;
    }
});

	
		
window.WineListItemView = Backbone.View.extend({

    tagName: "li",

    className: "span3",

    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
	      events: {
		'click .toggle': 'toggleCompleted',
		'click .plain': 'toggleCompleted'
      },
       toggleCompleted: function(){
         this.model.toggle();
       },
    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }

});
