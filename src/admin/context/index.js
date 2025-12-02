import { createContext } from '@wordpress/element';

/**
 * @constant {import('react').Context<null>} AdminContext - A context object created using React's createContext API.
 * Initialized with a default value of null.
 */
const AdminContext = createContext(null);

export { AdminContext };
