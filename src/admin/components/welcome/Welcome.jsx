import {
	Button,
	CardBody,
	__experimentalHeading as Heading,
	__experimentalText as Text,
} from "@wordpress/components";
import { setActiveMenu } from "../../actions";
import { WELCOME } from "../../constants";
import { usePageflashContext } from "../../hooks";
import FeatureList from "./FeatureList";

const Welcome = () => {
	const { dispatch } = usePageflashContext();
	return (
		<div className="pageflash-welcome">
			<CardBody style={{ textAlign: "center", padding: "2rem" }}>
				<Heading
					level={2}
					style={{
						marginBottom: "0.5rem",
						fontSize: "50px",
						fontWeight: "bold",
					}}
				>
					{WELCOME["label"]}
				</Heading>
				<Text
					style={{
						fontSize: "20px",
						color: "#555",
						marginBottom: "2rem",
					}}
				>
					{WELCOME["description"]}
				</Text>
				<FeatureList features={WELCOME["featureList"]} />
				<Button
					isPrimary
					onClick={() => {
						dispatch(setActiveMenu("settings"));
					}}
				>
					{WELCOME["buttonText"]}
				</Button>
			</CardBody>
		</div>
	);
};

export default Welcome;
