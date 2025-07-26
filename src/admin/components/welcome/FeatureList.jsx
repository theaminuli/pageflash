import { Flex } from '@wordpress/components';
const FeatureList = ( { features } ) => {
	return (
		<div
			className="pageflash-welcome__features"
			style={ { textAlign: 'left', maxWidth: '500px', margin: '0 auto' } }
		>
			<ul
				className="pageflash-welcome__features-list"
				style={ {
					padding: 0,
					listStyle: 'none',
					marginBottom: '2rem',
					marginTop: '2rem',
				} }
			>
				{ features.map( ( feature, index ) => (
					<li
						key={ feature.id }
						style={ {
							display: 'flex',
							alignItems: 'center',
							marginBottom: '10px',
						} }
					>
						<Flex justify="left">
							<span
								style={ {
									backgroundColor:
										index === 0
											? '#E91E63'
											: index === 1
											? '#2196F3'
											: '#FF9800',
								} }
								className="pageflash-welcome__features-badge"
							>
								{ feature.title }
							</span>
							<span className="pageflash-welcome__features-title">
								{ feature.description }
							</span>
						</Flex>
					</li>
				) ) }
			</ul>
		</div>
	);
};
export default FeatureList;
