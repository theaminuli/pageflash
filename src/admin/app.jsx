/**
 * WordPress dependencies.
 */
import {
	Button,
	Card,
	CardBody,
	CardHeader,
	__experimentalHeading as Heading,
	__experimentalHStack as HStack,
	__experimentalVStack as VStack,
	__experimentalZStack as ZStack,
} from "@wordpress/components";
import { useViewportMatch } from "@wordpress/compose";
import {
	close,
	cog,
	external,
	home,
	Icon,
	key,
	menu,
	plugins,
} from "@wordpress/icons";
/**
 * Internal dependencies.
 */
import { useState } from "react";

/**
 * Render Shell 1
 */
function App() {
	const isMobile = !useViewportMatch("mobile");
	const [showButtons, setShowButtons] = useState(false);

	const buttonData = [
		{ icon: home, label: "Welcome" },
		{ icon: cog, label: "Settings" },
		{ icon: plugins, label: "Addons" },
		{ icon: key, label: "License" },
		{ icon: external, label: "Go Support" },
	];
	return (
		<>
			<HStack expanded={false} style={{ padding: "12px 23px" }}>
				<img
					width="100px"
					style={{ minWidth: "auto" }}
					src="https://raw.githubusercontent.com/lubusIN/wpui/main/src/img/logo.png"
				></img>
				{isMobile ? (
					<Button
						icon={showButtons ? close : menu}
						onClick={() => setShowButtons((prev) => !prev)}
					></Button>
				) : (
					<>
						<HStack expanded={false}>
							{buttonData.slice(0, -1).map((btn, index) => (
								<Button key={index}>
									<Icon style={{ minWidth: "25px" }} icon={btn.icon} />
									{btn.label}
								</Button>
							))}
						</HStack>
						<Button variant="primary" icon={external}>
							Go Support
						</Button>
					</>
				)}
			</HStack>
			<ZStack isReversed style={{ width: "100%" }}>
				{isMobile && showButtons && (
					<VStack
						style={{ padding: "12px", backgroundColor: "white" }}
						expanded={false}
					>
						{buttonData.map((btn, index) => (
							<Button
								key={index}
								icon={btn.icon}
								variant={btn.variant}
								style={
									btn.label === "Go Support" ? { justifyContent: "center" } : {}
								}
							>
								{btn.label}
							</Button>
						))}
					</VStack>
				)}
				<Card variant="secondary" isBorderless>
					<CardHeader isBorderless>
						<Heading level={2}>Welcome</Heading>
					</CardHeader>
					<CardBody>
						<Card
							variant="secondary"
							style={{ height: "300px", borderRadius: "10px" }}
						>
							{/* Display Your Content Here */}
						</Card>
					</CardBody>
				</Card>
			</ZStack>
			<style>
				{`
                    .components-z-stack >div{
                        width: 100%;
                    }
                `}
			</style>
		</>
	);
}

export default App;
