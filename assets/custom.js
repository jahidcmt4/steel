//load map on job search
jQuery(".geo_address_field").each(function (event) {
    var geolocation_search_item = jQuery(this);
    var input_id = geolocation_search_item.attr("id");
    var input = document.getElementById(input_id);
    var parent = jQuery(this).parent();
    var defaultBounds, autocomplete_normal;
  
    var geolocation_lat = parent.find(".geo_lat_data");
    var geolocation_long = parent.find(".geo_long_data");
  
    geolocation_search_item.on("change", function () {
      if (jQuery(this).val() === "") {
        geolocation_lat.val("");
        geolocation_long.val("");
        if (placeCircle != "") {
          placeCircle.setMap(null);
          placeCircle = "";
        }
      }
    });
  
    var iconSVG = '<svg width="20px" height="20px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000000"><path d="M12 27.2C12 46.4 32 56 32 56s20-9.6 20-28.8C52 16.6 43.05 8 32 8s-20 8.6-20 19.2z"/><circle cx="32" cy="26.88" r="6.88"/></svg>';
    var pac_input = this;
    // console.log(pac_input);
    var options = {
      componentRestrictions: {
        country: "uk",
      },
    };
    // create the autocomplete
    var autocomplete = new google.maps.places.Autocomplete(pac_input, options);
  
    // create an event listener on the autocomplete
    autocomplete.addListener("place_changed", function (e) {
      var place = autocomplete.getPlace();
      // console.log('helloooo');
  
      geolocation_lat.val(place.geometry.location.lat());
      geolocation_long.val(place.geometry.location.lng());
      //$(pac_input).siblings(".postcode").val(place.formatted_address);
      // console.log($('.autocomplete-field').val(place.formatted_address));
    });
  
    pac_input.addEventListener("change", function (e) {

    });
  
  
    jQuery(this).bind("keypress", function (e) {
      key = e.keyCode;
    });

  });

  //Job filter Js data
(function ($) {
    "use strict";

    steel_enable_slider_radius(".steel_slider_radius_search", ft_ajax_object.min_geo_radius, ft_ajax_object.max_geo_radius, ft_ajax_object.initial_radius);

    function steel_enable_slider_radius(slider_name, low_val, max_val, now_val) {
      var parent, geolocation_radius, radius_value;
  
      jQuery(".steel_slider_radius_search").each(function (event) {
        var selected_slider = $(this);
  
        var parent = selected_slider.parent();
        var geolocation_radius = parent.find(".geolocation_radius");
        var radius_value = parent.find(".radius_value");
  
        steel_enable_slider_radius_action(selected_slider, low_val, max_val, now_val, radius_value, geolocation_radius);
      });
    }
    /***********/
  
  function steel_enable_slider_radius_action(selected_slider, low_val, max_val, now_val, radius_value, geolocation_radius) {
    $(selected_slider).slider({
      range: true,
      min: parseFloat(low_val),
      max: parseFloat(max_val),
      value: parseFloat(now_val),
      range: "max",
      slide: function (event, ui) {
        geolocation_radius.val(ui.value);
        if (ft_ajax_object.geo_radius_measure === "miles") {
          radius_value.text(ui.value + " " + ft_ajax_object.miles);
        } else {
          radius_value.text(ui.value + " " + ft_ajax_object.km);
        }
      },
      stop: function (event, ui) {
        if (placeCircle != "") {
          if (ft_ajax_object.geo_radius_measure === "miles") {
            placeCircle.setRadius(ui.value * 1609.34);
          } else {
            placeCircle.setRadius(ui.value * 1000);
          }

          if (typeof wpestate_show_pins !== "undefined") {
            first_time_wpestate_show_inpage_ajax_half = 1;
            wpestate_show_pins();
          }
        }
      },
    });
  }
    
  // Sidebar Filter 
  var filter_xhr;
  const makeFilter = () => {

    var lat = $('#geo_lat_data').val();
    var long = $('#geo_long_data').val();
    var radius = $('#geolocation_radius').val();
    var startprice = $('.steel-product-filter-range input[name="from"]').val();
    var endprice = $('.steel-product-filter-range input[name="to"]').val();
    var orderby = $('#steel_orderby').val();


    let filters = termIdsByFeildName('product_cat');

    var formData = new FormData();
    formData.append('action', 'steel_trigger_filter');
    formData.append('lat', lat);
    formData.append('long', long);
    formData.append('radius', radius);
    formData.append('orderby', orderby);
    
    formData.append('filters', filters);

    if (startprice) {
        formData.append('startprice', startprice);
    }
    if (endprice) {
        formData.append('endprice', endprice);
    }

    // abort previous request
    if (filter_xhr && filter_xhr.readyState != 4) {
        filter_xhr.abort();
    }


    filter_xhr = $.ajax({
        type: 'post',
        url: ft_ajax_object.ajaxurl,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function (data) {
            
        },
        complete: function (data) {
            

        },
        success: function (response) {
          console.log(response);
          if (response.success) {
            $('.products.clearfix.columns-4').html(response.data.html); // Insert the product HTML into a container
            $('.woo_pagination').remove();
        }
        },
        error: function (data) {
            console.log(data);
        },

    });
  };


  $(document).on('change', '[name*=product_cat]', function () {
      if($(".steel-reset-button").length>0){
          $(".steel-reset-button").show();
      }
      makeFilter();
  });

  $(document).on('change', '#geo_address_field', function () { 
    if($(".steel-reset-button").length>0){
      $(".steel-reset-button").show();
    }
    setTimeout(function() {
      makeFilter();
    }, 1000); // 1 second delay
  });

  $(document).on('change', '#geolocation_radius', function () { 
    if($(".steel-reset-button").length>0){
      $(".steel-reset-button").show();
    }
    setTimeout(function() {
      makeFilter();
    }, 1000); // 1 second delay
  });

  $(document).on('change', '#steel_orderby', function (e) { 
    e.preventDefault();
    if($(".steel-reset-button").length>0){
      $(".steel-reset-button").show();
    }
    setTimeout(function() {
      makeFilter();
    }, 1000); // 1 second delay
  });

  const termIdsByFeildName = (fieldName) => {
    let termIds = [];
    $(`[name*=${fieldName}]`).each(function () {
        if ($(this).is(':checked')) {
            termIds.push($(this).val());
        }
    });
    return termIds.join();
}
    /**
     * Min and Max Range Filtering
    */
    let steel_range_options = {
      range: {
          min: parseInt(ft_ajax_object.steel_min_price),
          max: parseInt(ft_ajax_object.steel_max_price),
          step: 5
      },
      initialSelectedValues: {
          from: parseInt(ft_ajax_object.steel_min_price),
          to: parseInt(ft_ajax_object.steel_max_price) / 2
      },
      grid: false,
      theme: "dark",
      onFinish: function () {
        makeFilter();
        if($(".steel-reset-button").length>0){
          $(".steel-reset-button").show();
        }
      }
  };
  if (ft_ajax_object.steel_max_price != 0) {
      $('.steel-product-filter-range').alRangeSlider(steel_range_options);
  }

})(jQuery);