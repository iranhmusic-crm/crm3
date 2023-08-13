/*
jQuery(function ($) {

	$('a').each(function() {
		url = $(this).attr('href');
		// console.log(url);

		if ((url.startsWith('http://') || url.startsWith('https://'))
				&& url.startsWith(window.location.origin) == false) {
			// console.log($(this).attr('target'));

			if ($(this).attr('target') == undefined)
				$(this).attr('target', '_blank');

			return;
		}
	});

	$('a').on('click', function(event) {
		url = $(this).attr('href');
		console.log(url);

		if ((url.startsWith('http://') || url.startsWith('https://'))
				&& url.startsWith(window.location.origin) == false)
			return;

		if (event.isDefaultPrevented())
			return

		event.preventDefault();

		$.ajax({
			'type': 'GET',
			'url': url,
			'headers': {
				'Authentication': 'Bearer XXX.YYY.ZZZ',
			},
			// 'data': postData,
			'success': function(response)
			{
				console.log(response);

				window.history.pushState(null, "", url);
        window.location.replace(url);

				$("body").html(response);
			},
			'error': function(jqXHR, textStatus, errorThrown)
			{
				console.log('Error', jqXHR, textStatus, errorThrown);
				// alert(textStatus);
			}
	  });
	});
});
*/

/*
const registerServiceWorker = async () => {
  if ('serviceWorker' in navigator) {
    try {
			console.log('before registering');
      const registration = await navigator.serviceWorker.register(
        globalBaseUrl + '/js/sw-add-token-to-request.js',
        {
          scope: globalBaseUrl + '/',
        }
      );
      if (registration.installing) {
        console.log('Service worker installing');
      } else if (registration.waiting) {
        console.log('Service worker installed');
      } else if (registration.active) {
        console.log('Service worker active');
      }
    } catch (error) {
      console.error(`Registration failed with ${error}`);
    }
	} else {
		console.error('Service workers are not supported.');
	}
};

registerServiceWorker();
*/
