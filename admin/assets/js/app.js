
var app = (function() {

    'use-strict';


    /**
     *    Description:
     *    Private functions goes here
     */
    function $(element) {
        return document.querySelector(element);
    }


    return {

        /**
         *    Description:
         *    Start javascript
         *
         *    Syntax:
         *    app.init();
         */
        init: function() {
            // load navigation
            this.navigation();
            // table dropdown
            this.tableDropdown();
            // search
            this.searchForm();
            // animate col
            panel.Animate('.col','view');
            // progress functions
            panel.progress($('.preloader'),function(num, span, wait){
                // remove loader
                if(num > 100){
                    $('.preloader').removeChild(span);
                    panel.fadeOut($('#loader'),1000);
                    clearTimeout(wait);
                }
            });


            // remplace input submit value on submit form
            if($('form')) {
                $('form').addEventListener('submit',function(){
                    $('input[type="submit"]').value = 'saving...'
                });
            }
        },

        /**
        *
        *   Search pages ,blocks and uploads
        *
        */
        searchForm: function(){
            // search pages on enter
            if($('#search')){
                $('#search').addEventListener('keyup',function(event){
                    if(event.keyCode == 13){
                       location.href= [
                           root, // site url
                           '/action/search/', // action
                           this.getAttribute('data-search'), // get data-search
                           '/',
                           this.value // value
                       ].join('');
                    }
                });
            }
            if($('#search-files')){
                // search files on enter
                $('#search-files').addEventListener('keyup',function(event){
                    if(event.keyCode == 13){
                       location.href= [
                           root, // site url
                           '/action/searchfiles/',
                           this.value // value
                       ].join('');
                    }
                });
            }
        },

        /**
         *    Description:
         *    navigation functions
         *
         *    Syntax:
         *    app.navigation && this.navigation
         */
        navigation: function() {
            // vars
            var menu = $('.menu'),
                i = $('.menu > i'),
                menuDiv = $('#menu'),
                wrapperDiv = $('#wrapper');
            // menu
            if (menu) {
                menu.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (panel.hasCls(i, 'ti-layout-sidebar-left')) {
                        panel.removeCls(i, 'ti-layout-sidebar-left');
                        panel.addCls(i, 'ti-layout-sidebar-right');
                    } else {
                        panel.addCls(i, 'ti-layout-sidebar-left');
                        panel.removeCls(i, 'ti-layout-sidebar-right');
                    }
                    panel.toggleCls(menuDiv, 'is-opened');
                    panel.toggleCls(wrapperDiv, 'menu-is-open');
                });

                // select all links of sidebar
                var navlink = document.querySelectorAll('.dropdown-link');
                // convert to array
                var toArray = Array.prototype.slice.call(navlink);
                // make forEach
                Array.prototype.forEach.call(toArray,function(selector,index){
                    // on click show drowdown and change arrow
                    selector.addEventListener('click', function(){
                        // check if has dropdown element
                        if(panel.hasCls(this.nextElementSibling,'dropdown')){
                            panel.toggleCls(this.nextElementSibling,'show_menu');
                            if(panel.hasCls(this.nextElementSibling,'show_menu')){
                                // check if get arrow
                                if(this.querySelector('i')){
                                    this.querySelector('i').className = '';
                                    this.querySelector('i').className = 'ti-angle-down';
                                }
                            }else{
                                // check if get arrow
                                if(this.querySelector('i')){
                                    this.querySelector('i').className = '';
                                    this.querySelector('i').className = 'ti-angle-right';
                                }
                            }
                        }
                    },false);
                });
            }
        },
        // get links of table dropdown
        tableDropdown: function(){
            var selectOption = document.querySelectorAll('.selectOption');
            // if exists
            if(selectOption){
                var selectOptionToArray = Array.prototype.slice.call(selectOption);
                Array.prototype.forEach.call(selectOptionToArray,function(selector,index){
                    selector.addEventListener('change',function(){
                        window.location.href= this.value;
                    },false);
                });
            }
        }
    };
})();

/*  on load
---------------------*/
window.addEventListener('load', function(){
    app.init();

});
