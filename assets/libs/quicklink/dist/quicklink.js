function e( e ) {
	return new Promise( function ( r, n, t ) {
		( t = new XMLHttpRequest() ).open(
			'GET',
			e,
			( t.withCredentials = ! 0 )
		),
			( t.onload = function () {
				200 === t.status ? r() : n();
			} ),
			t.send();
	} );
}
let r,
	n =
		( r = document.createElement( 'link' ) ).relList &&
		r.relList.supports &&
		r.relList.supports( 'prefetch' )
			? function ( e ) {
					return new Promise( function ( r, n, t ) {
						( ( t = document.createElement( 'link' ) ).rel =
							'prefetch' ),
							( t.href = e ),
							( t.onload = r ),
							( t.onerror = n ),
							document.head.appendChild( t );
					} );
			  }
			: e,
	t =
		window.requestIdleCallback ||
		function ( e ) {
			const r = Date.now();
			return setTimeout( function () {
				e( {
					didTimeout: ! 1,
					timeRemaining() {
						return Math.max( 0, 50 - ( Date.now() - r ) );
					},
				} );
			}, 1 );
		},
	o = new Set(),
	i = new Set(),
	c = ! 1;
function a( e ) {
	if ( e ) {
		if ( e.saveData ) {
			return new Error( 'Save-Data is enabled' );
		}
		if ( /2g/.test( e.effectiveType ) ) {
			return new Error( 'network conditions are poor' );
		}
	}
	return ! 0;
}
function s( r, t, s ) {
	const u = a( navigator.connection );
	return u instanceof Error
		? Promise.reject( new Error( 'Cannot prefetch, ' + u.message ) )
		: ( i.size > 0 &&
				! c &&
				console.warn(
					'[Warning] You are using both prefetching and prerendering on the same document'
				),
		  Promise.all(
				[].concat( r ).map( function ( r ) {
					if ( ! o.has( r ) ) {
						return (
							o.add( r ),
							( t
								? function ( r ) {
										return window.fetch
											? fetch( r, {
													credentials: 'include',
											  } )
											: e( r );
								  }
								: n )( new URL( r, location.href ).toString() )
						);
					}
				} )
		  ) );
}
function u( e, r ) {
	const n = a( navigator.connection );
	if ( n instanceof Error ) {
		return Promise.reject( new Error( 'Cannot prerender, ' + n.message ) );
	}
	if ( ! HTMLScriptElement.supports( 'speculationrules' ) ) {
		return (
			s( e ),
			Promise.reject(
				new Error(
					'This browser does not support the speculation rules API. Falling back to prefetch.'
				)
			)
		);
	}
	if ( document.querySelector( 'script[type="speculationrules"]' ) ) {
		return Promise.reject(
			new Error(
				'Speculation Rules is already defined and cannot be altered.'
			)
		);
	}
	for ( let t = 0, u = [].concat( e ); t < u.length; t += 1 ) {
		const f = u[ t ];
		if (
			window.location.origin !== new URL( f, window.location.href ).origin
		) {
			return Promise.reject(
				new Error( 'Only same origin URLs are allowed: ' + f )
			);
		}
		i.add( f );
	}
	o.size > 0 &&
		! c &&
		console.warn(
			'[Warning] You are using both prefetching and prerendering on the same document'
		);
	const l = ( function ( e ) {
		const r = document.createElement( 'script' );
		( r.type = 'speculationrules' ),
			( r.text =
				'{"prerender":[{"source": "list","urls": ["' +
				Array.from( e ).join( '","' ) +
				'"]}]}' );
		try {
			document.head.appendChild( r );
		} catch ( e ) {
			return e;
		}
		return ! 0;
	} )( i );
	return ! 0 === l ? Promise.resolve() : Promise.reject( l );
}
( exports.listen = function ( e ) {
	if ( ( e || ( e = {} ), window.IntersectionObserver ) ) {
		const r = ( function ( e ) {
				e = e || 1;
				let r = [],
					n = 0;
				function t() {
					n < e && r.length > 0 && ( r.shift()(), n++ );
				}
				return [
					function ( e ) {
						r.push( e ) > 1 || t();
					},
					function () {
						n--, t();
					},
				];
			} )( e.throttle || 1 / 0 ),
			n = r[ 0 ],
			a = r[ 1 ],
			f = e.limit || 1 / 0,
			l = e.origins || [ location.hostname ],
			d = e.ignores || [],
			h = e.delay || 0,
			p = [],
			m = e.timeoutFn || t,
			w = 'function' === typeof e.hrefFn && e.hrefFn,
			g = e.prerender || ! 1;
		c = e.prerenderAndPrefetch || ! 1;
		var v = new IntersectionObserver(
			function ( r ) {
				r.forEach( function ( r ) {
					if ( r.isIntersecting ) {
						p.push( ( r = r.target ).href ),
							( function ( e, r ) {
								r ? setTimeout( e, r ) : e();
							} )( function () {
								-1 !== p.indexOf( r.href ) &&
									( v.unobserve( r ),
									( c || g ) && i.size < 1
										? u( w ? w( r ) : r.href ).catch(
												function ( r ) {
													if ( ! e.onError ) {
														throw r;
													}
													e.onError( r );
												}
										  )
										: o.size < f &&
										  ! g &&
										  n( function () {
												s(
													w ? w( r ) : r.href,
													e.priority
												)
													.then( a )
													.catch( function ( r ) {
														a(),
															e.onError &&
																e.onError( r );
													} );
										  } ) );
							}, h );
					} else {
						const t = p.indexOf( ( r = r.target ).href );
						t > -1 && p.splice( t );
					}
				} );
			},
			{ threshold: e.threshold || 0 }
		);
		return (
			m(
				function () {
					( e.el || document )
						.querySelectorAll( 'a' )
						.forEach( function ( e ) {
							( l.length && ! l.includes( e.hostname ) ) ||
								( function e( r, n ) {
									return Array.isArray( n )
										? n.some( function ( n ) {
												return e( r, n );
										  } )
										: ( n.test || n ).call( n, r.href, r );
								} )( e, d ) ||
								v.observe( e );
						} );
				},
				{ timeout: e.timeout || 2e3 }
			),
			function () {
				o.clear(), v.disconnect();
			}
		);
	}
} ),
	( exports.prefetch = s ),
	( exports.prerender = u );
