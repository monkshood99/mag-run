( function(){
	var app = new Vue({
	  el: '#ran-today-form',
	  data: {
		  submitted : false,
		  message : '',
		  runs : [{ title : 'I Ran'}],
		  new_data : {
			  distance : 1,
			  date : $page_data.today 
		  }
	  },
	  methods: {
	    submit: function () {
				fetch("/?post-my-run", this.new_data ).then(function ( response ) {
					console.log( response )
				}).then(function ( response ) {
					console.log( response )
				}); 
		    
		    this.message = "Congratulations!";
		    this.runs.push(JSON.parse( JSON.stringify( this.new_data ) ) );
	    }
	  }
	})

})();


jQuery(function() {

  // page is now ready, initialize the calendar...

  jQuery('#calendar').fullCalendar({
    // put your options and callbacks here
  })

});

