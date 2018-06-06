String.prototype.ucfirst = function ()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
};
String.prototype.htmlEncode = function () {
    return this.replace(/\"/g,'&quot;') ;
};