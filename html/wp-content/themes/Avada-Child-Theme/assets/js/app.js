Vue.component('todo-item', {
  props: ['todo'],
  template: '<li>{{ todo.text }}</li>'
})
var app = new Vue({
  el: '#app',
  data: {
    message: 'Hello Vue.js!',
    seen : false,
    todos: [
      { text: 'Learn JavaScript' },
      { text: 'Learn Vue' },
      { text: 'Build something awesome' }
    ],
    html: "<h1>HI</h1>",
    number : 12,
    url : ''
  },
  methods: {
    reverseMessage: function () {
      this.message = this.message.split('').reverse().join('')
    },
    reverseMessageF: function () {
      return this.message.split('').reverse().join('')
    },
    toggleSeen: function () {
      this.seen = this.seen ? false : true; 
    }
  },
  computed: {
    reversedMessage: function () {
      return this.message.split('').reverse().join('')
    }
	},
  watch: {
    seen: function (nv , ov ) {
      console.log( nv, ov  )
    }
	},
  beforeCreate : function( x ){ console.log( x , 'beforeCreated' ) },
  created : function( x ){ console.log( x , 'created') },
  beforeMount : function( x ){ console.log( x , 'beforeMount' ) },
  mounted : function( x ){ console.log( x , 'mounted') },
  beforeDestroy : function( x ){ console.log( x , 'beforeDestroy' ) },
  destroyed : function( x ){ console.log( x , 'destroyed') },
  beforeUpdate : function( x ){  },
  updated : function( x ){ },
  
})



app.$watch( 'seen' , function( nv, ov ){
	console.log( nv, ov );
})