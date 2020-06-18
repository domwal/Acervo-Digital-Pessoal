var cookieList = function(cookieName) {
  var cookie = $.cookie(cookieName);
  var items = cookie ? cookie.split(/,/) : new Array();

  return {
    "add": function(val) {
        //Add to the items.
        items.push(val);
        //Save the items to a cookie.
        $.cookie(cookieName, items.join(','));
    },
    "contain": function (val) {
    //Check if an item is there.
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function(obj, start) {
            for (var i = (start || 0), j = this.length; i < j; i++) {
                if (this[i] === obj) { return i; }
            }
            return -1;
        };
    }
    var indx = items.join(',').indexOf(val);
    if(indx > -1){
        return true;
    }else{
        return false;
    }                                                 },
    "remove": function (val) {
        indx = items.indexOf(val);
        if(indx!=-1) items.splice(indx, 1);
        $.cookie(cookieName, items.join(','));        },
    "clear": function() {
        items = null;
        //clear the cookie.
        $.cookie(cookieName, null);
    },
    "items": function() {
        return items;
    }
  }
}