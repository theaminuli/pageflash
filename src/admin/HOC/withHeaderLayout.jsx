import { Header } from '../components/header';

const withHeaderLayout = ( WrappedComponent ) => {
	return function HeaderLayoutWrapper( props ) {
		return (
			<Header>
				<WrappedComponent { ...props } />
			</Header>
		);
	};
};
export default withHeaderLayout;
