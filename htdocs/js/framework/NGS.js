NGS = {
  namespace : "",
  loadsContainer : {}, //private attribute for collect all loads
  actionsContainer : {}, //private attribute for collect all actions
  nestedLoads : {}, //private attribute for collect all actions
  initialLoads : {
    "load" : null,
    "params" : {}
  }, //private attribute for collect initial loads (used in ngs mode)
  tmstDiff : 0,
  inited : false,

  /**
   * Method for creating load object
   *
   * @param  loadName:String
   * @param  loadObj:Object
   * @param  parentLoad:String
   *
   */
  getConfig : function() {
    if ( typeof (NGS.config) != "undefined") {
      return NGS.config;
    }
    return {};
  },

  /**
   * Method for creating load object
   *
   * @param  loadName:String
   * @param  loadObj:Object
   * @param  parentLoad:String
   *
   */
  createLoad : function(loadName, loadObj, parentLoad) {

    if ( typeof (parentLoad) == "function") {
      parentLoad = parentLoad;
    } else if ( typeof (parentLoad) == "string") {
      parentLoad = this._getLoadClass(parentLoad);
    } else {
      parentLoad = NGS.AbstractLoad;
    }
    var load = this._getLoadPackageAndName(loadName);
    load["klass"] = NGS.Class(loadObj, parentLoad);
    this.loadsContainer[loadName] = load;
  },

  /**
   * Method for getting load from loadname
   * clone current load with new object
   * do recursive extend from current loads parents objects
   *
   * @param  loadName:String
   *
   * return Object loadObject
   */
  _getLoadClass : function(loadName) {
    if ( typeof (loadName) == "string") {
      if ( typeof (this.loadsContainer[loadName]) === "object") {
        return this.loadsContainer[loadName]["klass"];
      } else {
        throw new Error(loadName + " load not found");
      }
    } else if (oneTimeAction == false) {
      throw new Error("load not found");
    }
  },

  /**
   * Method for getting load from loadname
   * clone current load with new object
   * do recursive extend from current loads parents objects
   *
   * @param  loadName:String
   *
   * return Object loadObject
   */
  _getLoadObject : function(loadName) {
    if ( typeof (loadName) == "string") {
      if ( typeof (this.loadsContainer[loadName]) === "object") {
        var load = this.loadsContainer[loadName];
        var newLoad = new load["klass"];
        newLoad.setPackage(load["package"]);
        newLoad.setName(load["action"]);
        return newLoad;
      } else {
        throw new Error(loadName + " load not found");
      }
    } else if (oneTimeAction == false) {
      throw new Error("load not found");
    }
  },

  /**
   * Method for run selected load
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  load : function(loadName, params) {
    try {
      this.nestedLoads = {};
      this._getLoadObject(loadName).load(params);
    } catch(e) {
      throw e;
    }

  },

  /**
   * Method for running nested loads
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  setNestedLoad : function(parent, loadName, params) {
    if ( typeof (this.nestedLoads[parent]) == "undefined") {
      this.nestedLoads[parent] = [];
    }
    this.nestedLoads[parent].push({
      "load" : loadName,
      "params" : params
    });
  },

  /**
   * Method for running nested loads
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  getNestedLoadByParent : function(parent) {
    if ( typeof (this.nestedLoads[parent]) != "undefined") {
      return this.nestedLoads[parent];
    }
    return null;
  },

  /**
   * Method for running nested loads
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  nestLoads : function() {
    try {
      for (var i = 0; i < this.nestedLoads.length; i++) {
        var nestLoad = this.nestedLoads[i];
        this._getLoadObject(nestLoad["load"]).nestLoad(nestLoad["params"]);
      }

    } catch(e) {
      throw e;
    }
  },

  /**
   * Method for running nested loads
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  nestLoad : function(loadName, params) {
    try {
      var laodObj = this._getLoadObject(loadName);
      laodObj.nestLoad(params);
      this.nestLoads();
    } catch(e) {
      throw e;
    }
  },

  /**
   * Method for creating action object
   *
   * @param  actionName:String
   * @param  inheritAction:Object
   * @param  actionObj:Object
   *
   */
  createAction : function(actionName, actionObj, parentAction) {
    if ( typeof (parentAction) !== "function") {
      parentAction = NGS.AbstractAction;
    }
    var action = this._getLoadPackageAndName(actionName);
    action["klass"] = NGS.Class(actionObj, parentAction);
    this.actionsContainer[actionName] = function(load) {
      var newAction = new action["klass"];
      newAction.setPackage(action["package"]);
      newAction.setName(action["action"]);
      return newAction;
    }.bind(this, action);
  },

  /**
   * Method for getting load from loadname
   * clone current load with new object
   * do recursive extend from current loads parents objects
   *
   * @param  loadName:String
   *
   * return Object loadObject
   */
  _getAction : function(action, oneTimeAction) {

    if ( typeof (action) == "string" && oneTimeAction === false) {
      if ( typeof (this.actionsContainer[action]) === "function") {
        return this.actionsContainer[action]();
      }
    } else if ( typeof (action) == "string" && oneTimeAction === true) {
      var action = this._getLoadPackageAndName(action);
      var newAction = new NGS.AbstractAction();
      newAction.setPackage(action["package"]);
      newAction.setName(action["action"]);
      return newAction;
    }
    throw new Error(action + " action not found");
  },

  /**
   * Method for sending single request
   *
   * @param  urlPath:String
   * @param  params:Object
   * @param  callBack:Function
   *
   */
  action : function(action, params, afterAction) {
    try {
      var oneTimeAction = false;
      if ( typeof (afterAction) == "function") {
        oneTimeAction = true;
      }
      var actionObj = this._getAction(action, oneTimeAction);
      if (oneTimeAction) {
        actionObj.afterAction = afterAction;
      }
      actionObj.action(params);
    } catch(e) {
      console.error(e);
    }
  },

  /**
   * Method for getting load and package from loadName

   * @param  loadName:String
   *
   * return Object loadObject
   */

  _getLoadPackageAndName : function(actionName) {
    var matches = actionName.match(/[a-zA-Z\_\-]+/g);
    var action = matches[matches.length - 1];
    var myRegExp = new RegExp('([A-Z])', 'g');
    action = action.replace(myRegExp, "_$1").toLowerCase().replace(new RegExp('^_'), "");
    var packges = matches.slice(0, matches.length - 1);
    var _package = "";
    if (packges.length > 0) {
      var deilm = "";
      for (var i = 0; i < packges.length; i++) {
        _package += deilm + packges[i];
        deilm = ".";
      }
    }
    return {
      "package" : _package,
      "action" : action
    };
  },

  api : function(_url, params, onSucces, onError) {
    if (this.getAccessToken() != null) {
      params.access_token = this.getAccessToken();
    }
    var _url = NGS.getConfig().apiUrl + "/" + _url.replace(/\./g, "/");
    var options = {
      method : "POST",
      params : params,
      crossDomain : true,
      onComplete : function(responseText) {
        onSucces(JSON.parse(responseText));
      }.bind(this),
      onError : function(responseText) {
        onError(JSON.parse(responseText));
      }.bind(this)
    };
    if (NGS.ut != null && NGS.ud != null) {
      options.params.ut = NGS.ut;
      options.params.ud = NGS.ud;
    }
    NGS.AjaxLoader.request(_url, options);
  },

  /**
   * namespace setter function
   *
   * @param  loadName:String
   * @param  params:Object
   *
   */
  setInitialLoad : function(loadName, params) {
    this.initialLoads["load"] = loadName;
    if (params) {
      this.initialLoads["params"] = params;
    }
  },

  /**
   * namespace getter function
   *
   * @return  namespace:String
   *
   */
  getInitialLoad : function() {
    return this.initialLoads;
  },

  /**
   * Hellper method for extend one object from other
   *
   * @param  obj:Object
   * @param  inheritObject:Object
   *
   */

  /**
   * namespace setter function
   *
   * @param  namespace:String
   *
   */
  setNamespace : function(namespace) {
    this.namespace = namespace;
  },
  /**
   * namespace getter function
   *
   * @return  namespace:String
   *
   */
  getNamespace : function() {
    return this.namespace;
  },

  /**
   * namespace setter function
   *
   * @param  namespace:String
   *
   */
  setAccessToken : function(accessToken) {
    this.accessToken = accessToken;
  },
  /**
   * namespace getter function
   *
   * @return  namespace:String
   *
   */
  getAccessToken : function() {
    return this.accessToken;
  },
  setTmst : function(tmst) {
    this.tmstDiff = new Date().getTime() - tmst;
  },

  getTmst : function() {
    return new Date().getTime() - this.tmstDiff;
  },

  Class : (function() {
    var subclass = new Function;
    function create() {
      var properties = arguments[0];
      var parent = null;
      if ( typeof (arguments[1]) == "function") {
        parent = arguments[1];
      }
      function IMklass() {
        this.initialize.apply(this, arguments);
      }


      IMklass.addMethods = addMethods;
      IMklass.superclass = parent;
      IMklass.subclasses = [];
      if (parent) {
        subclass.prototype = parent.prototype;
        IMklass.prototype = new subclass;
        parent.subclasses.push(IMklass);
      }
      IMklass.addMethods(properties);
      if (!IMklass.prototype.initialize)
        IMklass.prototype.initialize = new Function;
      IMklass.prototype.constructor = IMklass;
      return IMklass;
    }

    function addMethods(source) {
      var ancestor = this.superclass && this.superclass.prototype,
          properties = Object.keys(source);
      for (var i = 0,
          length = properties.length; i < length; i++) {
        var property = properties[i],
            value = source[property];
        if (!value) {
          continue;
        }
        if (ancestor && value.toString() == '[object Function]' && value.argumentNames()[0] == "$super") {
          var method = value;
          value = (function(m) {
            return function() {
              return ancestor[m].apply(this, arguments);
            };
          })(property).wrap(method);
          value.valueOf = (function(method) {
            return function() {
              return method.valueOf.call(method);
            };
          })(method);
          value.toString = (function(method) {
            return function() {
              return method.toString.call(method);
            };
          })(method);
        }
        this.prototype[property] = value;
      }
      return this;
    }

    return function() {
      return create.apply(this, arguments);
      // return create(arguments);
    };
  })(),

  ErrorException : function(message) {
    this.message = message;
    this.name = "UserException";
  },

  /**
   * Hellper method for extend one object from other
   *
   * @param  obj:Object
   * @param  inheritObject:Object
   *
   */
  extend : function(destination, source) {
    for (var property in source) {
      destination[property] = source[property];
    }
    return destination;
  },
  /**
   * Hellper method for getting empty function
   *
   */
  emptyFunction : function() {
    return;
  },
  /**
   * Hellper method for geglobal scope eval
   * We use an anonymous function so that context is window
   *
   */
  globalEval : function(data) {
    var rnotwhite = /\S/;
    if (data && rnotwhite.test(data)) {
      (window.execScript ||
      function(data) {
        window["eval"].call(window, data);
      } )(data);
    }
  }
};
