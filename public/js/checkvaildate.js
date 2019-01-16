/**
 * Created by Administrator on 2016-12-28.
 */


$(function() {




    var idCardNoUtil = {

        provinceAndCitys: {11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",
            31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",
            45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",
            65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"},


        powers: ["7","9","10","5","8","4","2","1","6","3","7","9","10","5","8","4","2"],


        parityBit: ["1","0","X","9","8","7","6","5","4","3","2"],


        genders: {male:"男",female:"女"},


        checkAddressCode: function(addressCode){
            var check = /^[1-9]\d{5}$/.test(addressCode);
            if(!check) return false;
            if(idCardNoUtil.provinceAndCitys[parseInt(addressCode.substring(0,2))]){
                return true;
            }else{
                return false;
            }
        },


        checkBirthDayCode: function(birDayCode){
            var check = /^[1-9]\d{3}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))$/.test(birDayCode);
            if(!check) return false;
            var yyyy = parseInt(birDayCode.substring(0,4),10);
            var mm = parseInt(birDayCode.substring(4,6),10);
            var dd = parseInt(birDayCode.substring(6),10);
            var xdata = new Date(yyyy,mm-1,dd);
            if(xdata > new Date()){
                return false;//生日不能大于当前日期
            }else if ( ( xdata.getFullYear() == yyyy ) && ( xdata.getMonth () == mm - 1 ) && ( xdata.getDate() == dd ) ){
                return true;
            }else{
                return false;
            }
        },


        getParityBit: function(idCardNo){
            var id17 = idCardNo.substring(0,17);

            var power = 0;
            for(var i=0;i<17;i++){
                power += parseInt(id17.charAt(i),10) * parseInt(idCardNoUtil.powers[i]);
            }

            var mod = power % 11;
            return idCardNoUtil.parityBit[mod];
        },


        checkParityBit: function(idCardNo){
            var parityBit = idCardNo.charAt(17).toUpperCase();
            if(idCardNoUtil.getParityBit(idCardNo) == parityBit){
                return true;
            }else{
                return false;
            }
        },


        checkIdCardNo: function(idCardNo){
//15位和18位身份证号码的基本校验
            var check = /^\d{15}|(\d{17}(\d|x|X))$/.test(idCardNo);
            if(!check) return false;
//判断长度为15位或18位
            if(idCardNo.length==15){
                return idCardNoUtil.check15IdCardNo(idCardNo);
            }else if(idCardNo.length==18){
                return idCardNoUtil.check18IdCardNo(idCardNo);
            }else{
                return false;
            }
        },

//校验15位的身份证号码
        check15IdCardNo: function(idCardNo){
//15位身份证号码的基本校验
            var check = /^[1-9]\d{7}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}$/.test(idCardNo);
            if(!check) return false;
//校验地址码
            var addressCode = idCardNo.substring(0,6);
            check = idCardNoUtil.checkAddressCode(addressCode);
            if(!check) return false;
            var birDayCode = '19' + idCardNo.substring(6,12);
//校验日期码
            return idCardNoUtil.checkBirthDayCode(birDayCode);
        },

//校验18位的身份证号码
        check18IdCardNo: function(idCardNo){
//18位身份证号码的基本格式校验
            var check = /^[1-9]\d{5}[1-9]\d{3}((0[1-9])|(1[0-2]))((0[1-9])|([1-2][0-9])|(3[0-1]))\d{3}(\d|x|X)$/.test(idCardNo);
            if(!check) return false;
//校验地址码
            var addressCode = idCardNo.substring(0,6);
            check = idCardNoUtil.checkAddressCode(addressCode);
            if(!check) return false;
//校验日期码
            var birDayCode = idCardNo.substring(6,14);
            check = idCardNoUtil.checkBirthDayCode(birDayCode);
            if(!check) return false;
//验证校检码
            return idCardNoUtil.checkParityBit(idCardNo);
        },

        formateDateCN: function(day){
            var yyyy =day.substring(0,4);
            var mm = day.substring(4,6);
            var dd = day.substring(6);
            return yyyy + '-' + mm +'-' + dd;
        },

//获取信息
        getIdCardInfo: function(idCardNo){
            var idCardInfo = {
                gender:"", //性别
                birthday:"" // 出生日期(yyyy-mm-dd)
            };
            if(idCardNo.length==15){
                var aday = '19' + idCardNo.substring(6,12);
                idCardInfo.birthday=idCardNoUtil.formateDateCN(aday);
                if(parseInt(idCardNo.charAt(14))%2==0){
                    idCardInfo.gender=idCardNoUtil.genders.female;
                }else{
                    idCardInfo.gender=idCardNoUtil.genders.male;
                }
            }else if(idCardNo.length==18){
                var aday = idCardNo.substring(6,14);
                idCardInfo.birthday=idCardNoUtil.formateDateCN(aday);
                if(parseInt(idCardNo.charAt(16))%2==0){
                    idCardInfo.gender=idCardNoUtil.genders.female;
                }else{
                    idCardInfo.gender=idCardNoUtil.genders.male;
                }

            }
            return idCardInfo;
        },


        getId15:function(idCardNo){
            if(idCardNo.length==15){
                return idCardNo;
            }else if(idCardNo.length==18){
                return idCardNo.substring(0,6) + idCardNo.substring(8,17);
            }else{
                return null;
            }
        },


        getId18: function(idCardNo){
            if(idCardNo.length==15){
                var id17 = idCardNo.substring(0,6) + '19' + idCardNo.substring(6);
                var parityBit = idCardNoUtil.getParityBit(id17);
                return id17 + parityBit;
            }else if(idCardNo.length==18){
                return idCardNo;
            }else{
                return null;
            }
        }
    };
//验证护照是否正确
    function checknumber(number){
        var str=number;
//在JavaScript中，正则表达式只能使用"/"开头和结束，不能使用双引号
        var Expression=/(P\d{7})|(G\d{8})|(S\d{8})|(S\d{7})|(D\d{8})|(E\d{8})/;
        var objExp=new RegExp(Expression);
        if(objExp.test(str)==true){
            return true;
        }else{
            return false;
        }
    };

    jQuery.validator.addMethod("checkMbile", function(value, element) {
        var length = value.length;
        return this.optional(element) || (length == 11 && /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|16[0-9]|17[0-9]|18[0-9]|19[0-9])\d{8}$/.test(value));
    }, "手机号码格式错误!");

    jQuery.validator.addMethod("chinese", function(value, element) {
        var chinese = /^[\u4e00-\u9fa5]+$/;
        return this.optional(element) || (chinese.test(value));
    }, "只能输入中文");

    jQuery.validator.addMethod("checkName", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9-_]{6,12}$/.test(value);
    }, "用户名只能为英文字母下划线和数字(6-12)位");

    jQuery.validator.addMethod("checkPwd", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9-_]{6,12}$/.test(value);
    }, "密码只能为英文字母下划线和数字(6-12)位");


	 jQuery.validator.addMethod("checkPyWb", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    },"只能输入字母数字");
	
   jQuery.validator.addMethod("checkTel", function(value, element) {
        return this.optional(element) || /^((0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(value);
    }, "固话号码格式为：区号-号码-分机号");
	
	
	jQuery.validator.addMethod("isNumber", function(value, element) {       
         return this.optional(element) || /^[-\+]?\d+$/.test(value) || /^[-\+]?\d+(\.\d+)?$/.test(value);       
    }, "只能输入数值类型");
	
	
	jQuery.validator.addMethod("isWord", function(value, element) {       
         return this.optional(element) || /^[a-zA-Z]+$/.test(value);       
    }, "只能输入英文字母");
	
	
	jQuery.validator.addMethod("isInt", function(value, element) {       
         return this.optional(element) || /^[1-9]\d*$/.test(value);       
    }, "只能输入整数");
	
	// 身份证号码验证 
    jQuery.validator.addMethod("isIdCardNo", function(value, element) { 
        return this.optional(element) || idCardNoUtil.checkIdCardNo(value) || checknumber(value);
    }, "请正确输入身份证号码"); 
    
    //护照编号验证
    jQuery.validator.addMethod("passport", function(value, element) {
        return this.optional(element) || checknumber(value);     
    }, "请正确输入您的护照编号"); 
   
    // 手机号码验证 
    jQuery.validator.addMethod("isMobile", function(value, element) { 
        var length = value.length; 
        var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/; 
        return this.optional(element) || (length == 11 && mobile.test(value)); 
    }, "请正确填写手机号码"); 
  
    // 电话号码验证 
    jQuery.validator.addMethod("isTel", function(value, element) { 
        var tel = /^\d{3,4}-?\d{7,9}$/; //电话号码格式010-12345678 
        return this.optional(element) || (tel.test(value)); 
    }, "请正确填写电话号码"); 
 
    // 联系电话(手机/电话皆可)验证 
    jQuery.validator.addMethod("isPhone", function(value,element) { 
        var length = value.length; 
        var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/; 
        var tel = /^\d{3,4}-?\d{7,9}$/; 
        return this.optional(element) || (tel.test(value) || mobile.test(value)); 
    }, "请正确填写联系电话"); 
  
    // 邮政编码验证 
    jQuery.validator.addMethod("isZipCode", function(value, element) { 
        var tel = /^[0-9]{6}$/; 
        return this.optional(element) || (tel.test(value)); 
    }, "请正确填写邮政编码"); 
	
	//验证ip或url
	jQuery.validator.addMethod("isIpOrUrl", function(value,element) { 
       
        var ipaddr = /^([0-9]{1,3}\.){3}[0-9]{1,3}$/; 
        var urladdr =/^([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
        return this.optional(element) || (ipaddr.test(value) || urladdr.test(value)); 
    }, "请正确填写IP或URL");



    // 项目数值结果参考值验证
    jQuery.validator.addMethod("isNumJg", function(value, element) {
        var NumJg =  /^[>≥<≤]?[-]?[0-9]+([.]{1}[0-9]+){0,1}$/; //数值结果正则
        return this.optional(element) || (NumJg.test(value));
    }, "参考值格式为 a:数值 b:≥数值 c:>数值 d:<数值  e:≤数值");
	
	//输入日期格式验证
	jQuery.validator.addMethod("isDate", function(value, element){  
    var ereg = /^(\d{1,4})(-|\/)(\d{1,2})(-|\/)(\d{1,2})$/;  
    var r = value.match(ereg);  
    if (r == null) {  
        return false;  
    }  
    var d = new Date(r[1], r[3] - 1, r[5]); 
	alert(d.getFullYear());
    var result = (d.getFullYear() == r[1] && Number(d.getMonth() + 1) == Number(r[3]) && Number(d.getDate()) == Number(r[5]));  
     
    return this.optional(element) || (result);  
}, "请输入正确的日期");  


});


