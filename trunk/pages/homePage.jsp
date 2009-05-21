<jsp:root	version="2.0"
			xmlns:jsp="http://java.sun.com/JSP/Page"
			xmlns:f="http://java.sun.com/jsf/core" 
			xmlns:h="http://java.sun.com/jsf/html"
			xmlns:a4j="https://ajax4jsf.dev.java.net/ajax"
			xmlns:rich="http://richfaces.org/rich">
<jsp:directive.page contentType="text/html; charset=UTF-8"/>

	<f:view>
		<html>
			<head>
				<style>
					.even {
					 	background-color: green;
					 }
					 .odd {
					 	background-color: red;
					 }
				</style>
			</head>
			<body>
				<a4j:form>
					<h:messages />
					<a4j:outputPanel>
						<rich:calendar value="#{sampleBean.date}" id="cal"
							datePattern="dd-MM-yyyy" locale="nl"
							currentDateChangeListener="#{sampleBean.currentDateChangeListener}" 
							valueChangeListener="#{sampleBean.valueChangeListener}"
							ajaxSingle="false"
							showInput="true"
							popup="false" mode="ajax" 
							dataModel="#{sampleBean.model}"
							binding="#{sampleBean.calendar}">
								<a4j:support reRender="text" event="ondateselected" actionListener="#{sampleBean.actionListener}" />
						</rich:calendar>
					</a4j:outputPanel> 
					<br /><br /><br />
					<h:outputText id="text" value="#{sampleBean.date}" />
					<br /><br />
					<h:commandLink>ble</h:commandLink>
					<rich:progressBar minValue="1" maxValue="10" value="3" enabled="true" />
				</a4j:form> 
			</body>
		</html>
	</f:view>
</jsp:root>

