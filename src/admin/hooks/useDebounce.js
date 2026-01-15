import { useEffect, useState } from 'react';

/**
 * Custom React hook that debounces a value by delaying updates until after a specified delay period.
 * 
 * @param {*} value - The value to be debounced
 * @param {number} delay - The delay in milliseconds before updating the debounced value
 * @returns {*} The debounced value that updates after the specified delay
 * 
 * @example
 * const [searchTerm, setSearchTerm] = useState('');
 * const debouncedSearchTerm = useDebounce(searchTerm, 500);
 * 
 */
function useDebounce(value, delay) {
	const [debouncedValue, setDebouncedValue] = useState(value);

	useEffect(() => {
		const handler = setTimeout(() => {
			setDebouncedValue(value);
		}, delay);

		return () => clearTimeout(handler); // Cleanup on every value change
	}, [value, delay]);

	return debouncedValue;
}
export default useDebounce;