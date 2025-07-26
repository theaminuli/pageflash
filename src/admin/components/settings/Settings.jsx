/**
 * WordPress dependencies.
 */
import { useState } from "react";

/**
 * WordPress dependencies.
 */
import {
	Card,
	CardBody,
	__experimentalHeading as Heading,
	__experimentalHStack as HStack,
	__experimentalText as Text,
	ToggleControl,
	__experimentalVStack as VStack,
} from "@wordpress/components";
import { toast } from "react-toastify";
import { usePageflashContext } from "../../hooks";
/**
 * Render Action panel
 */
const Settings = () => {
	const [value, setValue] = useState(false);
	const { dispatch } = usePageflashContext();

	const handleChange = (newValue) => {
		setValue((newValue) => !newValue);
		if (newValue) {
			toast.success("Settings enabled!", {
				className: "pageflash-settings__success",
				autoClose: 2000,
				hideProgressBar: false,
				closeOnClick: true,
				pauseOnHover: true,
				draggable: true,
				progress: undefined,
			});
		} else {
			toast.info("Settings disabled.", {
				className: "pageflash-settings__info",
				autoClose: 2000,
				hideProgressBar: false,
				closeOnClick: true,
				pauseOnHover: true,
				draggable: true,
				progress: undefined,
			});
		}
	};
	return (
		<HStack alignment="center" className="pageflash-settings">
			<Card size="large" style={{ borderRadius: "8px" }}>
				<CardBody>
					<VStack spacing={2}>
						<Heading level={4}>Backup Your Website Automatically</Heading>
						<HStack
							direction={["column", "row"]}
							alignment={"start"}
							justify="left"
						>
							<VStack>
								<Text
									size={14}
									lineHeight={1.6}
									weight={400}
									style={{ maxWidth: "600px" }}
									variant="muted"
								>
									Automate the safeguarding of your valuable content with
									scheduled automatic backups, ensuring the continuous
									protection of your website data against unforeseen events.
								</Text>
							</VStack>
							<ToggleControl
								__nextHasNoMarginBottom
								checked={value}
								onChange={handleChange}
							/>
						</HStack>
					</VStack>
				</CardBody>
			</Card>
		</HStack>
	);
};

export default Settings;
