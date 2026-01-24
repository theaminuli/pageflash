/**
 * WordPress dependencies
 */
import { memo } from '@wordpress/element';
/**
 * Internal dependencies
 */
import { SKELETON_OPTIONS } from './constants';
/**
 * External dependencies
 */
import ContentLoader from 'react-content-loader';

/**
 * Responsive Skeleton component
 */
const Skeleton = memo( () => {
	return (
		<div className="skeleton-wrapper">
			{ /* Header */ }
			<div className="skeleton-header">
				<div className="skeleton-header-left">
					<span className="logo"></span>
					<span className="logo-name"></span>
				</div>
				<div className="skeleton-header-center"></div>
				<div className="skeleton-header-right"> </div>
			</div>

			<div className="skeleton-main">
				{ /* Sidebar */ }
				<div className="skeleton-sidebar">
					{ Array.from( { length: 8 } ).map( ( _, i ) => (
						<div key={ i } className="skeleton-sidebar-row"></div>
					) ) }
				</div>

				{ /* Content area */ }
				<div className="skeleton-content">
					{ Array.from( { length: 8 } ).map( ( _, i ) => (
						<div key={ i } className="skeleton-row">
							<div className="skeleton-row-left">
								<div className="skeleton-row-title"></div>
								<div className="skeleton-row-desc"></div>
							</div>
							<div className="skeleton-row-toggle"></div>
						</div>
					) ) }
				</div>
			</div>
		</div>
	);
} );

/**
 * Dashboard loader wrapper
 */
const DashboardLoader = memo( ( { children } ) => (
	<>
		<Skeleton />
		{ children }
	</>
) );

export default DashboardLoader;
