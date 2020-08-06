$(function(){
  var init = function(){
    $('.btn-try').click(tryOnClick);
    $('.btn-switch').click(switchOnClick);
    $('.form-explorer').submit(formOnSubmit);
    $('.form-explorer').each(function(i, el){
      createHeader($(el));
      createBody($(el), {});
    });
  },
  tryOnClick = function(e){
    $(this).parents('.panel').find('.panel-footer').slideToggle();
  },
  switchOnClick = function(e){
    var a_input_type = ['form', 'json'];
    var $form = $(this).parents('.form-explorer');
    var body = $form.find('.body-group');
    var input_type = body.data('input_type');
    var index = a_input_type.indexOf(input_type);
    index = index === false ? 0 : (index + 1) % a_input_type.length;
    var values = getValues(body);
    body.data('input_type', a_input_type[index]);
    createBody($form, values);
  },
  inputOnInput = function(e){
    $(this).parents('.form-group').children('h5').children('.is-send').prop('checked', true);
  },
  btnAddOnClick = function(e){
    var formGroup = $(this).parent().parent();
    var sub_validate = formGroup.data('array');
    var arrayGroup = formGroup.children('.array-group');
    createInput(arrayGroup, false, sub_validate);
  },
  btnRemoveOnClick = function(e){
    var arrayItem = $(this).parent().parent().children('.array-group').children(':last-child');
    if(arrayItem.length == 0) return
    arrayItem.remove();
  },
  formOnSubmit = function(e){
    e.preventDefault();
    var $form = $(this);

    if($form.find('input[type="file"]').length > 0){
      return upload($form);
    }else{
      return ajax($form);
    }
  },
  ajax = function($form) {
    var param = getParam($form);

    $.ajax({
      'url': $form.attr('action'),
      'method': getMethod($form),
      'headers': param.header,
      'data': param.body,
      'contentType': param.contentType,
      'dataType': 'json',
      'success': function(json){
        $form.find('.result').removeClass('text-danger').show().html(syntaxHighlight(json));
      },
      'error': function(resp){
        $form.find('.result').addClass('text-danger').show().html(resp.responseText);
      }
    });
    return false;
  },
  getParam = function($form){
    var method = getMethod($form);
    var header = getHeader($form);
    var body = getValues($form.find('.body-group'));
    var contentType;

    switch (method) {
      case 'GET':
        contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        break;
      case 'POST':
        contentType = 'application/json';
        body = JSON.stringify(body);
        break;
    }

    return {
      'body': body,
      'header': header,
      'contentType': contentType
    };
  },
  getHeader = function($form) {
    var header = {};
    $form.find('.header-group').children('.form-group').each(function(i, el){
      var var_name = $(this).data('var_name');
      header[var_name] = getValueTextbox($(this));
      if(var_name === 'Authorization' && header[var_name]){
        setCookie('header-Authorization', header[var_name], 1);
        $('input[name="Authorization"]').val(header[var_name]);
      }
    });
    return header;
  },
  upload = function($form) {
    var xhr = new XMLHttpRequest();
    xhr.open('post', $form.attr('action'), true);
    // xhr.setRequestHeader("Content-Type","multipart/form-data");
    xhr.onreadystatechange = function(e) {
      if(this.readyState == 4){
        var is_error = false;
        var result;
        if(this.status == 200) {
          try{
            result = JSON.parse(this.responseText);
            result = syntaxHighlight(result)
          }catch{
            result = this.responseText;
            is_error = true;
          }
        }else{
          result = this.responseText;
          is_error = true;
        }
        if(!is_error){
          $form.find('.result').removeClass('text-danger').show().html(result);
        }else{
          $form.find('.result').addClass('text-danger').show().html(result);
        }
      }
    };
    var headers = getHeader($form);
    for(var name in headers){
      xhr.setRequestHeader(name, headers[name]);
    }
    var form_data = genFormData(getValues($form.find('.body-group')));
    xhr.send(form_data);
  },
  genFormData = function(body, form_data, prefix) {
    if(!form_data) form_data = new FormData();
    for(var key in body) {
      var name = (!prefix) ? key : prefix+'['+key+']';
      var value = body[key];
      switch(true){
        case value instanceof File:
        case typeof value === 'string':
          form_data.append(name, value);
          break;
        case value instanceof HTMLInputElement:
          if(value.type == 'file'){
            form_data.append(name, value.files[0]);
          }else{
            form_data.append(name, value);
          }
          break;
        case value instanceof Object:
          genFormData(value, form_data, name);
          break;
        default:
          // console.log('default',typeof value)
          break;
      }
    }
    return form_data
  }
  isNull = function($formGroup){
    var $checkbox = $formGroup.children('h5').children('input.is-send');
    if($checkbox.length > 0){
      if(!$checkbox.is(':checked')) return true;
    }
    return false;
  },
  isRequired = function(validate){
    return $.inArray('required', validate.split('|')) !== -1;
  },
  getInputType = function(validate){
    var a_validate = validate.split('|');
    if($.inArray('file', a_validate) !== -1) return 'file';
    for(var i = 0; i < a_validate.length; i++){
      if(/^array/.test(a_validate[i])) return 'array';
      if(/^json/.test(a_validate[i])) return 'json';
      if(/^enum/.test(a_validate[i])) return 'dropdown';
    }
    return 'textbox';
  },
  getMethod = function($el){
    var $form = $el.prop('tagName')=='FORM' ? $el : $el.parents('form');
    return $form.attr('method');
  },
  getJsonData = function($el){
    var bodyGroup = $el.hasClass('body-group') ? $el : $el.parents('.body-group');
    if(bodyGroup.length == 0) return {};
    return bodyGroup.data('json');
  },
  getValues = function(body){
    switch (body.data('input_type')) {
      case 'form':
      default:
        return getValuesForm(body)
      case 'json':
        return getValuesJson(body)
    }
  },
  getValuesForm = function(body){
    var method = getMethod(body);
    var values = {};
    body.children('.form-group').each(function(i, el){
      var var_name = $(el).data('var_name');
      var value = getValue($(el));
      if(method === 'GET' && value === null) return;
      values[var_name] = value;
    });
    return values;
  },
  getValuesJson = function(body){
    try {
      var value_json = body.children('textarea').val();
      return $.parseJSON(value_json);
    } catch (e) {
      return {};
    }
  },
  getValue = function($el){
    var type = $el.data('type');
    var value = null;
    switch(type){
      case 'textbox':
        value = getValueTextbox($el);
        break;
      case 'file':
        value = getValueFile($el);
        break;
      case 'array':
        value = getValueArray($el);
        break;
      case 'json':
        value = getValueJson($el);
        break;
      case 'dropdown':
        value = getValueDropdown($el);
        break;
    }
    return value;
  },
  getValueTextbox = function($formGroup){
    if(isNull($formGroup)) return null;
    return $formGroup.children('.input-wrap').children('input').val();
  },
  getValueArray = function($formGroup){
    if(isNull($formGroup)) return null;
    var method = getMethod($formGroup);
    var values = [];
    $formGroup.children('.array-group').children().each(function(i, el){
      var value = getValue($(el));
      if(method === 'GET' && value === null) return;
      values.push(value)
    });
    return values;
  },
  getValueJson = function($formGroup){
    if(isNull($formGroup)) return null;
    var method = getMethod($formGroup);
    var json_data = getJsonData($formGroup);

    var values = {};
    var json_name = $formGroup.data('json');

    if(!json_data[json_name]) return getValue($formGroup);

    $formGroup.children('.json-group').children().each(function(i, el){
      var $el = $(el);
      var var_name = $el.data('var_name');
      if(json_data[json_name] && !json_data[json_name][var_name]) return;
      var value = getValue($el);
      if(method === 'GET' && value === null) return;
      values[var_name] = value;
    });

    return values;
  },
  getValueDropdown = function($formGroup){
    if(isNull($formGroup)) return null;
    return $formGroup.children('.input-wrap').children('select').val();
  },
  getValueFile = function($formGroup) {
    if(isNull($formGroup)) return null;
    return $formGroup.children('input[type="file"]')[0];
  },
  createHeader = function($form){
    var header = $form.find('.header-group');
    if(header.length > 0){
      header.data('header').forEach(function(var_name){
        var textbox = createTextbox(var_name, true);
        if(var_name === 'Authorization'){
          var value = getCookie('header-Authorization');
          textbox.find('input[type="text"]').val(value);
        }
        header.append(textbox);
      });
    }
  },
  createBody = function($form, values){
    console.log(values)
    var body = $form.find('.body-group');
    body.children().remove();
    switch (body.data('input_type')) {
      case 'form':
      default:
        createBodyForm(body, values)
        break;
      case 'json':
        createBodyJson(body, values)
        break;
    }
  },
  createBodyForm = function(body, values){
    body.data('body').forEach(function(data){
      var input = createInput(body, data.name, data.validate, values[data.name]);
      input.addClass('top-body-group');
    });
  },
  createBodyJson = function(body, values){
    var formGroup = createFormGroup('jsonbody', true);
    formGroup.data('type', 'jsonbody');

    var inputWrap = $('<div class="input-wrap"></div>');
    formGroup.append(inputWrap);

    var values_json = JSON.stringify(values, undefined, 2);

    var input = $('<textarea class="form-control"></textarea>')
    input.attr('name', 'jsonbody');
    input.on('input', inputOnInput);
    input.val(values_json);
    input.attr('rows', (values_json.match(/\n/g) || []).length+1)
    inputWrap.append(input);

    body.append(input);

    return formGroup;
  },
  createInput = function(parent, var_name, validate, value, json_data){
    console.log(var_name,value)
    var is_required = isRequired(validate)
    var input_type = getInputType(validate);
    var input;
    if(!json_data) json_data = getJsonData(parent);

    switch(input_type){
      case 'textbox':
        input = createTextbox(var_name, is_required, value);
        break;
      case 'file':
        input = createFile(var_name, is_required);
        break;
      case 'array':
        input = createArray(var_name, is_required, validate, value, json_data);
        break;
      case 'json':
        input = createJson(var_name, is_required, validate, value, json_data);
        break;
      case 'dropdown':
        input = createDropdown(var_name, is_required, validate, value);
        break;
    }
    parent.append(input);
    return input;
  },
  createFormGroup = function(var_name, is_required){
    var formGroup = $('<div class="form-group"><h5></h5></div>');
    if(var_name !== false){
      formGroup.data('var_name', var_name);
      formGroup.find('h5').text(var_name + ' : ');
      if(!is_required){
        var checkbox = $('<input type="checkbox" class="is-send"> ');
        formGroup.find('h5').prepend(checkbox);
      }
    }
    return formGroup;
  },
  createTextbox = function(var_name, is_required, value){
    var formGroup = createFormGroup(var_name, is_required);
    formGroup.data('type', 'textbox');

    var inputWrap = $('<div class="input-wrap"></div>');
    formGroup.append(inputWrap);

    var input = $('<input type="text" class="form-control">')
    input.attr('name', var_name);
    input.on('input', inputOnInput);
    input.val(value);
    inputWrap.append(input);

    if(!is_required && value) {
      formGroup.children('h5').children('.is-send').prop('checked', true)
    }

    return formGroup;
  },
  createFile = function(var_name, is_required){
    var formGroup = createFormGroup(var_name, is_required);
    formGroup.data('type', 'file');

    var input = $('<input type="file">')
    input.attr('name', var_name);
    input.on('input', inputOnInput);

    formGroup.append(input);
    return formGroup;
  },
  createArray = function(var_name, is_required, validate, values, json_data){
    var a_validate = validate.split('|');
    var sub_validate;
    for(var i = 0; i < a_validate.length; i++){
      var match = /^array(\.(.+))?$/.exec(a_validate[i]);
      if(!match) continue;

      sub_validate = match[2]
      break;
    }

    var formGroup = createFormGroup(var_name, is_required);
    formGroup.data('type', 'array');
    formGroup.data('array', sub_validate);

    var btnAdd = $('<button type="button" class="btn btn-primary btn-xs" style="line-height:1;">+</button>');
    btnAdd.click(btnAddOnClick);
    formGroup.find('h5').append(btnAdd);

    var btnRemove = $('<button type="button" class="btn btn-primary btn-xs" style="line-height:1;">-</button>');
    btnRemove.click(btnRemoveOnClick);
    formGroup.find('h5').append(btnRemove);

    var arrayGroup = $('<div class="array-group"></div>');
    formGroup.append(arrayGroup);

    if(values && Array.isArray(values)){
      for(var i = 0; i < values.length; i++) {
        createInput(arrayGroup, false, sub_validate, values[i], json_data);
      }

      if(!is_required) {
        formGroup.children('h5').children('.is-send').prop('checked', true)
      }
    }else{
      createInput(arrayGroup, false, sub_validate, null, json_data);
    }

    return formGroup;
  },
  createJson = function(var_name, is_required, validate, values, json_data){
    var a_validate = validate.split('|');
    var json_name;
    for(var i = 0; i < a_validate.length; i++){
      var match = /^json(\.(.+))?$/.exec(a_validate[i]);
      if(!match) continue;

      json_name = match[2];
      break;
    }
    if(!json_data[json_name]) json_name = 'string';

    var formGroup = createFormGroup(var_name, is_required);
    formGroup.data('type', 'json');
    formGroup.data('json', json_name);

    var jsonGroup = $('<div class="json-group"></div>');
    formGroup.append(jsonGroup);

    Object.keys(json_data[json_name]).forEach(function(var_name){
      var value = null;
      if(values !== null && typeof values === 'object' && var_name in values){
        value = values[var_name];
        if(!is_required) formGroup.children('h5').children('.is-send').prop('checked', true);
      }
      createInput(jsonGroup, var_name, json_data[json_name][var_name], value, json_data);
    })

    return formGroup;
  },
  createDropdown = function(var_name, is_required, validate, value){
    var a_validate = validate.split('|');
    for(var i = 0; i < a_validate.length; i++){
      var match = /^enum\((.+)\)$/.exec(a_validate[i]);
      if(!match) continue;

      choice_str = match[1];
      break;
    }

    var formGroup = createFormGroup(var_name, is_required);
    formGroup.data('type', 'dropdown');

    var inputWrap = $('<div class="input-wrap"></div>');
    formGroup.append(inputWrap);

    var select = $('<select class="form-control"></select>')
    select.attr('name', var_name);
    select.on('change', inputOnInput);
    inputWrap.append(select);

    var choices = choice_str.split(';');
    choices.forEach(function(choice){
      var option = $('<option></option>').text(choice).attr('value', choice);
      select.append(option);
    })

    select.val(value);
    if(!is_required && value) {
      formGroup.children('h5').children('.is-send').prop('checked', true)
    }

    return formGroup;
  };

  init();
  $("pre.json").each(function(i,el){
      $(el).html(syntaxHighlight($(el).data('json')))
  });
});

function syntaxHighlight(json) {
  if (typeof json != 'string') {
    json = JSON.stringify(json, undefined, 2);
  }
  json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
    var cls = 'number';
    if (/^"/.test(match)) {
        if (/:$/.test(match)) {
            cls = 'key';
        } else {
            cls = 'string';
        }
    } else if (/true|false/.test(match)) {
        cls = 'boolean';
    } else if (/null/.test(match)) {
        cls = 'null';
    }
    return '<span class="' + cls + '">' + match + '</span>';
  });
}

function getCookie(name) {
  var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
  return v ? v[2] : null;
}

function setCookie(name, value, days) {
  var d = new Date;
  d.setTime(d.getTime() + 24*60*60*1000*days);
  document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

function deleteCookie(name) { setCookie(name, '', -1); }