
var panel = (function() {

    'use-strict';
 
    return {

        /*
        *
        *   open modal lik accordion
        *   panel.modal(btn,dest,class)
        */
        modal: function(btn,dest,cls){
            var buttom = document.querySelector(btn),
                modal = document.querySelector(dest);
            if(btn){
                buttom.addEventListener('click',function(e){
                    e.preventDefault(e);
                    panel.toggleCls(modal,cls);
                });
            }
        },

        // get image preview
        media: function() {
        // demo img
        var imageDisplay = document.querySelector('#image-display');
            if(imageDisplay){
            var demo = imageDisplay.getAttribute('src'),
                database = window.localStorage;
                // clear first
              database.setItem( "image-base64",'');
              if(!database.getItem( "image-base64" )){
                var t = setTimeout(function(){
                    database.setItem( "image-base64", demo );
                  clearTimeout(t);
                },100);
              }
              
              /** @type {Node} */
              var imgInput = document.querySelector( "#image-input" ),
                  /** @type {Node} */
                  imgContainer = document.querySelector( "#image-display" ),
                  /** Restore image src from local storage */
                  updateUi = function() {
                      var t2 = setTimeout(function(){
                      imgContainer.src = database.getItem( "image-base64" );
                         clearTimeout(t2);
                      },200);
                  },
                  /** Register event listeners */
                  bindUi = function(){
                    imgInput.addEventListener( "change", function(){
                      if ( this.files.length ) {
                        var reader = new FileReader();
                        reader.onload = function( e ){
                          database.setItem( "image-base64", e.target.result );
                          updateUi();
                        };
                        reader.readAsDataURL( this.files[ 0 ] );
                      }
                    }, false );
                  };
              updateUi();
              bindUi();
          }
        },



         progress: function(selector,callback) {
            var span = document.createElement("span"),a = 0;
            span.className = "bar", 
            span.style.position = "absolute", 
            span.style.bottom = "0", 
            span.style.left = "0", 
            span.style.height = "4px", 
            span.style.display = "block", 
            span.style.background = "#f06565", 
            span.style.width = "0%", 
            span.style.zIndex = "999", 
            selector.appendChild(span);
            var wait = setInterval(function() {
                if (/loaded|complete/.test(document.readyState)) {
                    var num = 10 * a++;
                    return span.style.width = num + "%", callback ? callback(num, span, wait) : void 0
                }
            }, 10);
        },

        // each array
        Each: function(el, callback) {
            var allDivs = document.querySelectorAll(el),
                alltoArr = Array.prototype.slice.call(allDivs);
            Array.prototype.forEach.call(alltoArr, function(selector, index) {
                if (callback) return callback(selector);
            });
        },
        // Animate single items
        Animate: function(item,cls) {
            return (function show(counter) {
                setTimeout(function() {
                    var element = document.querySelectorAll(item)[counter];
                    if (typeof element != 'undefined') element.classList.add(cls);
                    counter++;
                    if (counter < document.querySelectorAll(item).length) show(counter);
                }, 50);
            })(0);
        },

        /**
         * Ajax GET request
         *
         * @param url
         * @param callback function
         * @return response
         */
        ajaxGet: function(url, callback) {

            var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            xhr.open('GET', url);
            xhr.onreadystatechange = function() {
                if (xhr.readyState > 3 && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send();

            return xhr;
        },

        /**
         * Ajax POST request
         *
         * @param url post to
         * @param data send to
         * @param callback function
         * @return response
         */
        ajaxPost: function(url, data, callback) {

            var params = typeof data == 'string' ? data : Object.keys(data).map(
                function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
                }
            ).join('&');

            var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
            xhr.open('POST', url);
            xhr.onreadystatechange = function() {
                if (xhr.readyState > 3 && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(params);

            return xhr;
        },

        /**
         * Find next element
         *
         * @param element
         * @return next element
         */
        nextElement: function(element) {

            do {
                element = element.nextSibling;
            } while (element && element.nodeType !== 1);

            return element;
        },

        /**
         * Find previous element
         *
         * @param element
         * @return next element
         */
        previousElement: function(element) {

            do {
                element = element.previousSibling;
            } while (element && element.nodeType !== 1);

            return element;
        },

        /**
         * Fade in animation
         *
         * @param element
         * @param animation speed
         * @param callback function
         */
        fadeIn: function(element, speed, callback) {

            if (!element.style.opacity) {
                element.style.opacity = 0;
            }

            var start = null;
            window.requestAnimationFrame(function animate(timestamp) {
                start = start || timestamp;
                var progress = timestamp - start;
                element.style.opacity = progress / speed;
                if (progress >= speed) {
                    if (callback && typeof(callback) === "function") {
                        callback();
                    }
                } else {
                    window.requestAnimationFrame(animate);
                }
            });
        },

        /**
         * Fade Out animation
         *
         * @param element
         * @param animation speed
         * @param callback function
         */
        fadeOut: function(element, speed, callback) {

            if (!element.style.opacity) {
                element.style.opacity = 1;
            }

            var start = null;
            window.requestAnimationFrame(function animate(timestamp) {
                start = start || timestamp;
                var progress = timestamp - start;
                element.style.opacity = 1 - progress / speed;
                if (progress >= speed) {
                    if (callback && typeof(callback) === "function") {
                        callback();
                    }
                } else {
                    window.requestAnimationFrame(animate);
                }
            });
        },

        /**
         * set browser vendor properties
         *
         * @param element
         * @param property CSS3 set of specifications e.g Transition
         * @param value CSS3 set of specifications e.g Transition speed '1s'
         */
        setVendor: function(element, property, value) {

            element.style["webkit" + property] = value;
            element.style["Moz" + property] = value;
            element.style["ms" + property] = value;
            element.style["o" + property] = value;
        },

        /**
         * Generate Random Integer
         *
         * @param min value of integer
         * @param max value of integer
         * @return integer
         */
        randomInt: function(min, max) {

            return Math.floor(Math.random() * (max - min + 1) + min);
        },

        /**
         * Get current url segment
         *
         * @param index of url integer
         * @return string url segment
         */
        urlSegment: function(index) {

            var url = window.location.pathname.split('/');
            return url[index];
        },

        /**
         * Check if element contains class
         *
         * @param element
         * @param class
         * @return boolean
         */
        hasCls: function(element, className) {

            return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
        },

        /**
         * Add Class
         *
         * @param element
         * @param className to add
         */
        addCls: function(element, className) {

            if (element.classList) {
                element.classList.add(className);
            } else {
                element.className += ' ' + className;
            }
        },

        /**
         * Remove Class
         *
         * @param element
         * @param className to remove
         */
        removeCls: function(element, className) {

            if (element.classList) {
                element.classList.remove(className);
            } else {
                element.className = element.className.replace(new RegExp(className, "g"), "");
            }

        },

        /**
         * Toggle class
         *
         * @param element
         * @param className to toggle
         */
        toggleCls: function(element, className) {

            if (element.classList) {
                element.classList.toggle(className);
            } else {
                var classes = element.className.split(' ');
                var existingIndex = classes.indexOf(className);

                if (existingIndex >= 0) {
                    classes.splice(existingIndex, 1);
                } else {
                    classes.push(className);
                }

                el.className = classes.join(' ');
            }

        },

        /**
         * Matches
         *
         * @param element
         * @param selector
         * @return boolean
         * e.g matches(document.getElementById('selector'), '#selector')
         */
        matches: function(element, selector) {

            return (element.matches ||
                element.matchesSelector ||
                element.msMatchesSelector ||
                element.mozMatchesSelector ||
                element.webkitMatchesSelector ||
                element.oMatchesSelector).call(element, selector);
        },

        /**
         * String starts with
         *
         * @param string
         * @param string suffix
         * @return boolean
         */
        startsWith: function(string, suffix) {

            return (string.substring(0, suffix.length) === suffix);

        },

        /**
         * String ends with
         *
         * @param string
         * @param string suffix
         * @return boolean
         */
        endsWith: function(string, suffix) {

            return string.indexOf(suffix, string.length - suffix.length) !== -1;
        },

        /**
         * String between two characters
         *
         * @param string
         * @param start first character
         * @param end second character
         * @return string
         */
        stringBetween: function(string, start, end) {

            return string.substring(string.lastIndexOf(start) + 1, string.lastIndexOf(end));
        },

        /**
         * In Array
         *
         * @param array
         * @param value check to
         * @return boolean
         */
        inArray: function(array, value) {

            for (var i = 0; i < array.length; i++) {
                if (array[i] === value) {
                    return true;
                }
            }
            return false;
        }


    };
})();


