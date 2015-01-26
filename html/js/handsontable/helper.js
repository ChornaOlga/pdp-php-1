/*
 * https://developer.mozilla.org/en-US/docs/Web/Events/DOMContentLoaded
 *
 * contentloaded.js
 *
 * Author: Diego Perini (diego.perini at gmail.com)
 * Summary: cross-browser wrapper for DOMContentLoaded
 * Updated: 20101020
 * License: MIT
 * Version: 1.2
 *
 * URL:
 * http://javascript.nwbox.com/ContentLoaded/
 * http://javascript.nwbox.com/ContentLoaded/MIT-LICENSE
 *
 */

// @win window reference
// @fn function reference
// function contentLoaded(win, fn) {

//   var done = false, top = true,

//     doc = win.document,
//     root = doc.documentElement,
//     modern = doc.addEventListener,

//     add = modern ? 'addEventListener' : 'attachEvent',
//     rem = modern ? 'removeEventListener' : 'detachEvent',
//     pre = modern ? '' : 'on',

//     init = function(e) {
//       if (e.type == 'readystatechange' && doc.readyState != 'complete') return;
//       (e.type == 'load' ? win : doc)[rem](pre + e.type, init, false);
//       if (!done && (done = true)) fn.call(win, e.type || e);
//     },

//     poll = function() {
//       try { root.doScroll('left'); } catch(e) { setTimeout(poll, 50); return; }
//       init('poll');
//     };

//   if (doc.readyState == 'complete') fn.call(win, 'lazy');
//   else {
//     if (!modern && root.doScroll) {
//       try { top = !win.frameElement; } catch(e) { }
//       if (top) poll();
//     }
//     doc[add](pre + 'DOMContentLoaded', init, false);
//     doc[add](pre + 'readystatechange', init, false);
//     win[add](pre + 'load', init, false);
//   }
// }


function ajax(url, method, params, callback) {
  var obj;

  try {
    obj = new XMLHttpRequest();
  } catch (e) {
    try {
      obj = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        obj = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (e) {
        alert("Your browser does not support Ajax.");
        return false;
      }
    }
  }
  obj.onreadystatechange = function () {
    if (obj.readyState == 4) {
      callback(obj);
    }
  };
  obj.open(method, url, true);
  obj.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  obj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  obj.send(params);

  return obj;
}

// (function () {

//   // function bindDumpButton() {

//   //   Handsontable.Dom.addEvent(document.body, 'click', function (e) {

//   //     var element = e.target || e.srcElement;

//   //     if (element.nodeName == "BUTTON" && element.name == 'dump') {
//   //       var name = element.getAttribute('data-dump');
//   //       var instance = element.getAttribute('data-instance');
//   //       var hot = window[instance];
//   //       console.log('data of ' + name, hot.getData());
//   //     }
//   //   });
//   // }

//   function init() {
//     hljs.initHighlighting();

//     bindDumpButton();
//   }

//   // var initAll = function () {
//     init();
//   // };

//   // contentLoaded(window, function (event) {
//   //   initAll();
//   // });

// //if(document.addEventListener) {
// //  document.addEventListener('DOMContentLoaded', initAll, false);
// //} else {
// //  document.attachEvent('DOMContentLoaded', initAll);
// //}


// })();

