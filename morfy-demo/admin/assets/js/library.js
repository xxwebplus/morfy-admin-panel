var panel = (function() {

    'use-strict';

    return {



        /**
         * wrap function javascript
         * panel.wrap(selector,'<p>','</p>',callback);
         *
         * @param el
         * @param before
         * @param after
         * @param callback function
         * @return callback
         */
        wrap: function(el, before, after, callback) {
            inside = el.innerHTML;
            new_html = [before, inside, after].join('');
            el.innerHTML = new_html;
            var wait = setTimeout(function() {
                clearTimeout(wait);
                if (callback) return callback();
            }, 400);
        },




        /*
         *
         * open modal link accordion
         * panel.modal(el,dest,class)
         *
         * @param el
         * @param dest
         * @param cls
         */
        modal: function(el, dest, cls) {
            var buttom = document.querySelector(el),
                modal = document.querySelector(dest),
                close = document.querySelector('.close-modal');
            if (buttom) {
                buttom.addEventListener('click', function(e) {
                    e.preventDefault(e);
                    panel.toggleCls(modal, cls);
                });
                if (close) {
                    close.addEventListener('click', function(e) {
                        e.preventDefault(e);
                        panel.toggleCls(modal, cls);
                    });
                }
            }
        },





        /**
         * media preview function javascript
         * use for input file and display in div
         * panel.media(el,display);
         *
         * @param el
         * @param callback function
         * @return callback
         */
        media: function(el, display) {
            // demo img
            var imageDisplay = document.querySelector(display);
            if (imageDisplay) {
                var demo = imageDisplay.getAttribute('src'),
                    database = window.localStorage;
                // clear first
                database.setItem("image-base64", '');
                if (!database.getItem("image-base64")) {
                    var t = setTimeout(function() {
                        database.setItem("image-base64", demo);
                        clearTimeout(t);
                    }, 100);
                }

                /** @type {Node} */
                var imgInput = document.querySelector(el),
                    /** @type {Node} */
                    imgContainer = document.querySelector(display),
                    /** Restore image src from local storage */
                    updateUi = function() {
                        var t2 = setTimeout(function() {
                            imgContainer.src = database.getItem("image-base64");
                            clearTimeout(t2);
                        }, 200);
                    },
                    /** Register event listeners */
                    bindUi = function() {
                        imgInput.addEventListener("change", function() {
                            if (this.files.length) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    database.setItem("image-base64",e.target.result);
                                    updateUi();
                                };
                                reader.readAsDataURL(this.files[0]);
                            }
                        }, false);
                    };
                updateUi();
                bindUi();
            }
        },


        /**
         * progress bar function javascript
         * panel.progress(el,function(num,span,interval){
         *  // callback goes here
         *  if(num === 100){
         *    clearInterval(interval);
         *    span.remove();
         *  }
         * });
         *
         * @param el
         * @param callback function(num,span,inteval)
         * @return callback
         */
        progress: function(el, callback) {
            var span = document.createElement("span"),
                a = 0;
            span.className = "bar",
                span.style.position = "absolute",
                span.style.bottom = "0",
                span.style.left = "0",
                span.style.height = "4px",
                span.style.display = "block",
                span.style.background = "#f06565",
                span.style.width = "0%",
                span.style.zIndex = "999";
                el.appendChild(span);
            var interval = setInterval(function() {
                if (/loaded|complete/.test(document.readyState)) {
                    var num = 10 * a++;
                    return span.style.width = num + "%",callback ? callback(num, span, interval) : void 0;
                }
            }, 10);
        },



        /**
         * each  function javascript
         * panel.Each(el,callback);
         *
         * @param el
         * @param callback function
         * @return callback
         */
        Each: function(el, callback) {
            var allDivs = document.querySelectorAll(el),
                alltoArr = Array.prototype.slice.call(allDivs);
            Array.prototype.forEach.call(alltoArr, function(el, index) {
                if (callback) return callback(el);
            });
        },



        /**
         * each  function javascript
         * panel.Animate(el,'class');
         *
         * @param el
         * @param cls
         */
        Animate: function(el, cls) {
            return (function show(counter) {
                setTimeout(function() {
                    var element = document.querySelectorAll(el)[counter];
                    if (typeof element != 'undefined')
                        element.classList.add(cls);
                    counter++;
                    if (counter < document.querySelectorAll(el).length)
                        show(counter);
                }, 50);
            })(0);
        },



        /**
         * Ajax GET request
         * panel.ajaxGet(url,callback);
         *
         * @param url
         * @param callback function
         * @return response
         */
        ajaxGet: function(url, callback) {

            var xhr = window.XMLHttpRequest ?
                new XMLHttpRequest() :
                new ActiveXObject('Microsoft.XMLHTTP');
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
         * panel.ajaxPost(url,data,callback);
         *
         * @param url post to
         * @param data send to
         * @param callback function
         * @return response
         */
        ajaxPost: function(url, data, callback) {

            var params = typeof data == 'string' ? data :
                Object.keys(data).map(
                    function(k) {
                        return encodeURIComponent(k) + '=' +
                            encodeURIComponent(data[k]);
                    }
                ).join('&');

            var xhr = window.XMLHttpRequest ?
                new XMLHttpRequest() :
                new ActiveXObject("Microsoft.XMLHTTP");
            xhr.open('POST', url);
            xhr.onreadystatechange = function() {
                if (xhr.readyState > 3 && xhr.status === 200) {
                    callback(xhr.responseText);
                }
            };
            xhr.setRequestHeader('X-Requested-With',
                'XMLHttpRequest');
            xhr.setRequestHeader('Content-Type',
                'application/x-www-form-urlencoded');
            xhr.send(params);

            return xhr;
        },



        /**
         * Find next element
         * panel.nextElement(el);
         *
         * @param element
         * @return next element
         */
        nextElement: function(el) {
            do el = el.nextSibling;
            while (el && el.nodeType !== 1);
            return el;
        },

        /**
         * Find previous element
         * panel.previusElement(el);
         *
         * @param element
         * @return next element
         */
        previousElement: function(element) {
            do el = el.previousSibling;
            while (el && el.nodeType !== 1);
            return el;
        },




        /**
         * Fade in animation
         * panel.fadeIn(el,speed,callback);
         *
         * @param el
         * @param animation speed
         * @param callback function
         */
        fadeIn: function(el, speed, callback) {

            if (!el.style.opacity) el.style.opacity = 0;

            var start = null;
            window.requestAnimationFrame(function animate(timestamp) {
                start = start || timestamp;
                var progress = timestamp - start;
                el.style.opacity = progress / speed;
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
         * panel.fadeOut(el,speed,callback);
         *
         * @param element
         * @param animation speed
         * @param callback function
         */
        fadeOut: function(el, speed, callback) {
            if (!el.style.opacity) el.style.opacity = 1;
            var start = null;
            window.requestAnimationFrame(function animate(timestamp) {
                start = start || timestamp;
                var progress = timestamp - start;
                el.style.opacity = 1 - progress / speed;
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
         * panel.setVendor(el,property,value);
         *
         * @param element
         * @param property CSS3 set of specifications e.g Transition
         * @param value CSS3 set of specifications e.g Transition speed '1s'
         */
        setVendor: function(el, property, value) {
            el.style["webkit" + property] = value;
            el.style["Moz" + property] = value;
            el.style["ms" + property] = value;
            el.style["o" + property] = value;
        },



        /**
         * Generate Random Integer
         * panel.randomInt(min,max);
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
         * panel.urlSegment(index);
         *
         * @param index of url integer
         * @return string url segment
         */
        urlSegment: function(index) {
            var url = window.location.pathname.split('/');
            return url[index];
        },



        /**
         * Check if el contains class
         * panel.hasCls(el, className);
         *
         * @param el
         * @param class
         * @return boolean
         */
        hasCls: function(el, className) {
            return (' ' + el.className + ' ')
                .indexOf(' ' + className + ' ') > -1;
        },



        /**
         * Add Class
         * panel.addCls(el, className);
         *
         * @param el
         * @param className to add
         */
        addCls: function(el, className) {
            if (el.classList) {
                el.classList.add(className);
            } else {
                el.className += ' ' + className;
            }
        },




        /**
         * Remove Class
         * panel.removeCls(el, className);
         *
         * @param el
         * @param className to remove
         */
        removeCls: function(el, className) {
            if (el.classList) {
                el.classList.remove(className);
            } else {
                el.className = el.className
                    .replace(new RegExp(className, "g"), "");
            }

        },




        /**
         * Toggle class
         * panel.toggleCls(el, className);
         *
         * @param el
         * @param className to toggle
         */
        toggleCls: function(el, className) {
            if (el.classList) {
                el.classList.toggle(className);
            } else {
                var classes = el.className.split(' ');
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
         * panel.matches(el, selector);
         *
         * @param el
         * @param selector
         * @return boolean
         * e.g matches(document.getElementById('selector'), '#selector')
         */
        matches: function(el, selector) {

            return (el.matches ||
                el.matchesSelector ||
                el.msMatchesSelector ||
                el.mozMatchesSelector ||
                el.webkitMatchesSelector ||
                el.oMatchesSelector).call(el, selector);
        },



        /**
         * String starts with
         * panel.startWith(string, suffix);
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
         * panel.endsWith(string, suffix);
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
         * panel.stringBetween(string, start, end);
         *
         * @param string
         * @param start first character
         * @param end second character
         * @return string
         */
        stringBetween: function(string, start, end) {
            return string.substring(
                string.lastIndexOf(start) + 1, string.lastIndexOf(end));
        },



        /**
         * In Array
         * panel.inArray(array, value);
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

