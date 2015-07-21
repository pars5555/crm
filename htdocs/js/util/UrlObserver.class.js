/**
 * this function handle framework
 * load url changes using history object
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @version 6.0
 */
(function(NGS) {
  NGS.UrlObserver = {
    load : null,
    onUrlUpdateHandle : function(e) {
      this.load = e.data.load;
      var params = {};
      params["load"] = this.load.getPackage() + "." + this.load.getName();
      params["params"] = this.load.getParams();
      var permalink = "";
      if (this.load.getPermalink() != null) {
        var permalink = this.load.getPermalink();
        if (permalink.indexOf("/") !== 0) {
          permalink = "/" + permalink;
        }
      }
      if (permalink == window.location.pathname) {
        return;
      }
      history.pushState(params, "", permalink);
    }
  };
  document.addEventListener("im-onUrlUpdate", NGS.UrlObserver.onUrlUpdateHandle);
  window.onpopstate = function(e) {
    NGS.events.onUrlChange.data = {
      "load" : e
    };
    document.dispatchEvent(NGS.events.onUrlChange);
    if ( typeof e.state == "object") {
      if ( typeof e.state.load == "string") {
        NGS.load(e.state.load, e.state.params);
      }
    }
  };
})(NGS);