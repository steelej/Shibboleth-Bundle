1. Configure security.yaml:

	security:
		
		providers:
			# configure your own user provider here
			
		firewalls:
			# ...
			shibboleth:
				guard:
					authenticators:
						- Queensu\Shibboleth\Security\ShibbolethAuthenticator
				logout:
					path: /logout
					success_handler: Queensu\Shibboleth\Security\ShibbolethLogoutSuccessHandler

				stateless: true
			# ...
		

2. Configure services.yaml:

	services:
		# ...
		Queensu\Shibboleth\Security\ShibbolethLogoutSuccessHandler:
			class: Queensu\Shibboleth\Security\ShibbolethLogoutSuccessHandler

		Queensu\Shibboleth\Security\ShibbolethAuthenticator:
			class: Queensu\Shibboleth\Security\ShibbolethAuthenticator
		# ...