import { Tabs } from "@wordpress/components";
import { useState } from "@wordpress/element";

/**
 * DynamicTabs
 *
 * A reusable tab component that dynamically generates Tabs, TabList, and TabPanels.
 *
 * @param {Array} tabs - Array of tab objects: [{ tabId, label, content }]
 * @param {string} defaultTabId - Default active tab ID.
 * @param {Object} tabsProps - Additional props for the <Tabs> component.
 * @param {Object} tabProps - Default props for each <Tabs.Tab>.
 * @param {Object} tabPanelProps - Default props for each <Tabs.TabPanel>.
 */
const DynamicTabs = ({
	tabs = [],
	defaultTabId,
	tabsProps = {},
	tabProps = {},
	tabPanelProps = {},
}) => {
	const [activeTab, setActiveTab] = useState(
		defaultTabId || tabs[0]?.tabId || "",
	);

	if (!tabs?.length) return null;

	return (
		<Tabs
			activeTabId={activeTab}
			onActiveTabIdChange={setActiveTab}
			onSelect={setActiveTab}
			{...tabsProps}
		>
			{/* Tab List */}
			<Tabs.TabList>
				{tabs?.map(({ tabId, label }) => (
					<Tabs.Tab key={tabId} tabId={tabId} {...tabProps}>
						{label}
					</Tabs.Tab>
				))}
			</Tabs.TabList>

			{/* Tab Panels */}
			{tabs?.map(({ tabId, content: Content }) => (
				<Tabs.TabPanel key={tabId} tabId={tabId} {...tabPanelProps}>
					{typeof Content === "function" ? <Content /> : Content}
				</Tabs.TabPanel>
			))}
		</Tabs>
	);
};

export default DynamicTabs;
