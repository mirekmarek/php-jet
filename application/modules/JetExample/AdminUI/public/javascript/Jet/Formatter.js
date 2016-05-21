/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package js.Jet
 * @subpackage Formatter
 */
dojo.require("dojo.number");
dojo.require("dojo.date.locale");
dojo.require("dojo.date.stamp");

Jet.Formatter = {
    URL: function(URL, a_target){
        if(!URL.length){
            return "";
        }
        if(!a_target){
            a_target = "_blank";
        }
        return "<a href='"+URL+"' target='"+a_target+"'>"+URL+"</a>";
    },

    Email: function(email){
        if(!email.length){
            return "";
        }
        return "<a href='mailto:"+dojo.trim(email)+"'>"+email+"</a>";
    },

    FileSize: function(file_size){

        var int_size = file_size * 1;
        if(isNaN(int_size)){
            return file_size;
        }

        var unit = "B";

        if(int_size > 1024){
            int_size /= 1024;
            unit = "KiB";
        }

        if(int_size > 1024){
            int_size /= 1024;
            unit = "MiB";
        }

        if(int_size > 1024){
            int_size /= 1024;
            unit = "GiB";
        }

        var places = Math.floor(int_size) != int_size ? 2 : 0;
        return Jet.Formatter.Number(int_size, {"places": places})+" "+unit;
    },

    Number: function(number, options){
        options = options || {};
        return dojo.number.format(number, options);
    },

    Date: function(value){
        if(!value) {
            return "";
        }
        var date = dojo.date.stamp.fromISOString(value);
        return dojo.date.locale.format(date, {selector:"date"});
    },

    Time: function(value){
        if(!value) {
            return "";
        }
        var date = dojo.date.stamp.fromISOString(value);
        return dojo.date.locale.format(date, {selector:"time"});
    },

    DateTime: function(value){
        if(!value) {
            return "";
        }
        var date = dojo.date.stamp.fromISOString(value);
        return dojo.date.locale.format(date);
    }
};
