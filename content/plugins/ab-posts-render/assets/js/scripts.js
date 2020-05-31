jQuery(document).ready(function ($) {

  /**
   * Insert an Advertisement Banner in the page
   * @param {string} v - The version of the template.
   */
  function appendFakeAds (v = 1) {
    let appended = false

    /**
     * generate HTML markup to insert into the page
     * @param {string} {v1, v2 or v3} - The version of the template.
     * @param {string} cssClass - add custom css class
     *
     */
    function getAdMarkup (type, cssClass = '') {
      if (type == 'v3') {
        return `
      <style>
      
   
      .ad-content{
          text-align: center;
          background: #006bb3;
          height: 100px;
      }
      
      .sidebar{
        z-index: 22;
        float: right;
        height:0px;
        width: 300px;
        color: #ce8888;
        position: relative;
        will-change: min-height;
        opacity: 0.5;
      }
      
      .sidebar__inner{
          transform: translate(0, 0); /* For browsers don't support translate3d. */
          transform: translate3d(0, 0, 0);
          will-change: position, transform;
      }

      </style>
      <div id="sidebarAd" class="sidebar">
        <div class="sidebar__inner">
            <div class="ad ${type} ${cssClass}" id="${type}Ad"><img src="https://dummyimage.com/100x500/006bb3/ffffff.png&text=${type}" alt=""></div>
        </div>
    </div>
`
      }

      if (type == 'v2') {
        return `
      <style>
      
      .sticky-ad.sticky-ad_bottom {
          top: unset;
          bottom: 0px !important;
          
      }
      .sticky-ad {
          opacity: 0.5;
          position: fixed;
          top: 0px;
          z-index: 22;
          background: #006bb3;
          width: 100%;
          text-align: center;
      }
      
      </style>
      <div class="ad ${type} ${cssClass}" id="${type}Ad"><img class="ad-content" src="https://dummyimage.com/600x100/006bb3/ffffff.png&text=${type}" alt=""></div>
`
      }
      if (type == 'v1') {
        return `
      <style>
      
      .ad-content{
          text-align: center;
          background: #006bb3;
          height: 100px;
      }
      </style>
      <div class="ad ${type} ${cssClass}" id="${type}Ad"><img class="ad-content" src="https://dummyimage.com/800x120/006bb3/ffffff.png&text=${type}" alt=""></div>
`
      }

    }

    /*
    * add behavior for the v1 ad template
    */
    function handleAdV1 () {
      let appended = $('.entry-content').prepend(getAdMarkup('v1')).length === 0 ? false : true

      $('article').appear()
      $('article').on('appear', function (event, $all_appeared_elements) {
        $('#v1Ad').show('slow')
      })
      $('article').on('disappear', function (event, $all_appeared_elements) {
        $('#v1Ad').hide('slow')
      })

      return appended
    }

    /*
    * add behavior for the v2 ad template
    */
    function handleAdV2 () {
      let appended = $('body').prepend(getAdMarkup('v2', 'sticky-ad')).length === 0 ? false : true
      $v2Ad = $('#v2Ad')

      var currentScrollTop = 0

      $(window).bind('scroll', function () {

        scrollTop = $(this).scrollTop()

        clearTimeout($.data(this, 'scrollTimer'))
        $.data(this, 'scrollTimer', setTimeout(function () {

          if (scrollTop > currentScrollTop) {
            //down scroll
            $v2Ad.addClass('sticky-ad_bottom')
          } else {
            //up scroll
            $v2Ad.removeClass('sticky-ad_bottom')
          }
          currentScrollTop = scrollTop

        }, 30))

      })

      return appended
    }

    /*
    * add behavior for the v3 ad template
    */
    function handleAdV3 () {
      let appended = $('article').prepend(getAdMarkup('v3')).length === 0 ? false : true
      $('article .entry-content,article .entry-header').css({display: 'inline-block'})
      $v3Ad = $('#sidebarAd')
      $v3Ad.stickySidebar({
        topSpacing: 60,
        bottomSpacing: 0
      })
      return appended
    }

    switch (v) {
      case 1 : {
        appended = handleAdV1()
        break
      }
      case 2 : {
        appended = handleAdV2()
        break
      }
      case 3 : {
        appended = handleAdV3()
        break
      }
    }

    return appended
  }

  /**
   * http://stackoverflow.com/a/10997390/11236
   */
  function updateURLParameter (url, param, paramVal) {
    let newAdditionalURL = ''
    let tempArray = url.split('?')
    const baseURL = tempArray[0]
    const additionalURL = tempArray[1]
    let temp = ''
    if (additionalURL) {
      tempArray = additionalURL.split('&')
      for (var i = 0; i < tempArray.length; i++) {
        if (tempArray[i].split('=')[0] != param) {
          newAdditionalURL += temp + tempArray[i]
          temp = '&'
        }
      }
    }

    const rows_txt = temp + '' + param + '=' + paramVal
    return baseURL + '?' + newAdditionalURL + rows_txt
  }

  // our code here
  if ($('body').hasClass('single-post')) {
    const queryString = window.location.search
    const urlParams = new URLSearchParams(queryString)
    if (urlParams.get('v') > 0 && urlParams.get('v') <= 3) {
      appendFakeAds(urlParams.get('v') * 1)
    } else {
      let randomAd = Math.floor(Math.random() * 3) + 1
      appendFakeAds(randomAd)
      window.history.replaceState('', '', updateURLParameter(window.location.href, 'v', randomAd))
    }
  }

})
