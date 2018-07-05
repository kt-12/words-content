Vue.component('kt12word-markup', {
    props:{
        'word-id': { required: true },
        'title': { required: true },
    },
    template:`
    <span class="kt12word-markup"  :class="{'kt12-active':isActive}" @click="getWordContent()" :title="title" >
    <slot></slot>
    <span :class="{'kt12word-markup-icon kt12-icon kt12-icon-plus-circle':!isActive, 'kt12word-markup-icon kt12-icon kt12-icon-minus-circle kt12-active':isActive}" ></span>
    </span>
    `,
    data(){
        return {
            isActiveState:false
        }
    },
    computed:{
        isActive(){
            //if slider is closed all the markup are in inactive state
            return ( this.$parent.showSidebar &&  this.isActiveState);
        }
    },
    methods:{
        getWordContent(){

            // logic to show hide sidebar
            this.$parent.showSidebar = this.isActiveState = !this.isActiveState;

            // logic to make other markups inactive
            this.$parent.$children.forEach(component => {
                if(component != this)
                {
                    component.isActiveState = false;
                }
            });

            if( this.isActiveState ){

                  //a detailed explination of ajax through axios can be found here https://kt12.in/blog/wordpress-ajax-call-using-axios-js/
                  if(this.$parent.nodes[this.wordId]){
                    //avoid expensive Ajax call
                    this.$parent.sidebar = this.$parent.nodes[ this.wordId ];
                } else {
                    // Ajax call to retrieve word and its content
                    this.$parent.ajaxloading = true;
                    axios.post(vue_object.ajax_url, Qs.stringify({
                        action: 'word_ajax_content_by_id',
                        kt12word_word_id: this.wordId,
                        security:vue_object.security_id,
                    })
                    ).then(function (response) {
                        this.$parent.ajaxloading = false;
                        if(response.data.status){
                            this.$parent.nodes[ this.wordId ] = this.$parent.sidebar = response.data.word;
                        }else {
                            console.log(response.data);
                            this.$parent.showSidebar = false;
                            alert("Something Just Went Wrong ! Try Again");
                        }
                    }.bind(this))
                    .catch(function (error) {
                        this.$parent.ajaxloading = false;
                        this.$parent.showSidebar = false;
                        alert("Something Just Went Wrong ! Try Again");
                        console.log(error);
                    });
                }
                this.$parent.$emit('make-ajax',this.wordId);
            }
        }
    }
});

Vue.component('kt12word-sidebar', {
    template:`
    <aside class="kt12word-sidebar" id="kt12word-sidebar" :word-id="$parent.sidebar.word_id">
    <header class="kt12word-head">
    <span class="kt12word-title">{{$parent.sidebar.title}}</span>
    <span class="kt12word-close kt12-icon kt12-icon-close" @click="closeSidebar" title="Close Sidebar"></span>
    </header>
    <section class="kt12word-body" v-html="$parent.sidebar.content" :style="$parent.isResized?$parent.sbBodyStyle:{}" ></section>
    <footer class="kt12word-foot" v-show="$parent.footer" v-html="$parent.footer"></footer>
    <div id="kt12word-loading-cover" v-show="$parent.ajaxloading"><div id="kt12word-loading"></div></div>
    </aside>
    `,
    methods:{
        closeSidebar(){
            //close sidebar
            this.$parent.showSidebar=false;
            //also mark all mark-up element to inactive
            this.$parent.$children.forEach(component => {
                component.isActiveState = false;
            });
        },
    },
    updated: function () {
        this.$nextTick(function () {
         externalSupport();
     });
    }
});

var app = new Vue({
    el:'#kt12word-root',
    data:{
        windowHeight:0,
        headerHeight:0,
        footerHeight:0,
        isResized: false,
        showSidebar:false,
        ajaxloading:false,
        sidebar:{
            'word_id' : 0,
            'title' : "Title is Loading...",
            'content': "Content is Loading...",
        },
        footer: vue_object.footer_content,
        nodes: [],

    },
    computed: {
        sbBodyStyle () {
            return {
                margin: this.headerHeight+"px 0px 0px",
                height: ( this.windowHeight - this.headerHeight - this.footerHeight) +"px"
            }
        }
    },
    mounted() {
        this.$nextTick(function() {
            window.addEventListener('resize', this.getWindowHeight);
        })
    },
    beforeDestroy: function () {
        window.removeEventListener('resize', this.getWindowHeight);
    },
    methods:{
        showPreview()
        {
            this.sidebar.word_id = -1;
            this.sidebar.title = document.getElementById('kt12word_title').value;
            content = getTinymceContent();

            if(content.trim() == ''){
                this.sidebar.content = '';
            }else{
            // Ajax call to retrieve word and its content
            this.ajaxloading = true;
            axios.post(vue_object.ajax_url, Qs.stringify({
                action: 'word_ajax_process_raw_content',
                kt12word_content: getTinymceContent(),
                security:vue_object.security_content,
            })
            ).then(function (response) {
                if(response.data.status){
                    this.ajaxloading = false;
                    this.sidebar.content = response.data.content;

                }else {
                    console.log(response.data);
                    alert("Something Just Went Wrong ! Try Again");
                    this.ajaxloading = false;
                    this.showSidebar = false;
                }
            }.bind(this))
            .catch(function (error) {
                this.showSidebar = false;
                alert("Something Just Went Wrong ! Try Again");
                console.log(error);
                this.ajaxloading = false;
            });
        }
        this.showSidebar =  true;
    },
    getWindowHeight(event) {
        this.isResized = true;
        this.windowHeight = document.documentElement.clientHeight;
        this.headerHeight = document.querySelector('.kt12word-head').offsetHeight;
        this.footerHeight = document.querySelector('.kt12word-foot').offsetHeight;

    }
}
});


// jquery dependency
getTinymceContent = function() {
    var editor_id = "kt12word_content";
    var textarea_id = editor_id;
    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
        return tinyMCE.get(editor_id).getContent();
    }else{
        return jQuery('#'+textarea_id).val();
    }
}

// Support for external scripts
externalSupport = function() {
    //sidebar height correction
    headerHeight = document.querySelector('.kt12word-head').offsetHeight;
    footerHeight = document.querySelector('.kt12word-foot').offsetHeight;
    contentHeight = document.documentElement.clientHeight - headerHeight - footerHeight ;
    document.querySelector('.kt12word-body').style.margin = headerHeight+"px 0px 0px";
    document.querySelector('.kt12word-body').style.height = contentHeight+"px";
    //height correction ends here.

    //scroll element to top on revisit
    contactFormSupport();
}

//support for contact form 7
contactFormSupport  = function() {
    //support for cf7 within sidebar
    if (typeof jQuery !== 'undefined' &&  typeof wpcf7 !== 'undefined' ) {
        // console.log(jQuery( 'div.wpcf7 > form' ));
        if( jQuery('span.ajax-loader').length == 0 ){
            jQuery( 'div.wpcf7 > form' ).each( function() {
                var $form = jQuery( this );
                wpcf7.initForm( $form );
                if ( wpcf7.cached ) {
                    wpcf7.refill( $form );
                }
            } );
        }
    }
}