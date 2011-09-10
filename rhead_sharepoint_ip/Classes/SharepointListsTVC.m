#import "SharepointListsTVC.h"
#import "SoapRequest.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "SharepointListVC.h"

@implementation SharepointListsTVC

@synthesize listsData, keysArr, selectedRowTitle, navCntrl;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.keysArr = [listsData allKeys];
	listTitleToImageNameMap = [[NSMutableDictionary alloc] init];
	[listTitleToImageNameMap setObject:@"calendar" forKey:@"Calendar"];
	//[listTitleToImageNameMap setObject:@"calendar" forKey:@"Links"];
	[listTitleToImageNameMap setObject:@"project_reports" forKey:@"Project Reports"];
	[listTitleToImageNameMap setObject:@"tasks" forKey:@"Tasks"];
	//[listTitleToImageNameMap setObject:@"calendar" forKey:@"Announcements"];
	[listTitleToImageNameMap setObject:@"site_photos" forKey:@"Site Progress Photos"];
	[listTitleToImageNameMap setObject:@"team_discusion" forKey:@"Team Discussion"];
	[listTitleToImageNameMap setObject:@"presentations" forKey:@"Presentations"];
	[listTitleToImageNameMap setObject:@"project_documents" forKey:@"Project Documents"];
	//[listTitleToImageNameMap setObject:@"calendar" forKey:@"Master Page Gallery"];
	[listTitleToImageNameMap setObject:@"shared_documents" forKey:@"Shared Documents"];
	/*
	listTitleToUrlAttributeName = [[NSMutableDictionary alloc] init];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Site Progress Photos"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Master Page Gallery"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Announcements"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Calendar"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Tasks"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Shared Documents"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Project Documents"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Presentations"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Project Reports"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Team Discussion"];
	[listTitleToUrlAttributeName setObject:@"ows_EncodedAbsUrl" forKey:@"Links"];
	 */
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [listsData count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:CellIdentifier] autorelease];
    }
    cell.textLabel.text = [listsData objectForKey:[keysArr objectAtIndex:indexPath.row]];
	cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
	if ([listTitleToImageNameMap objectForKey:cell.textLabel.text] != nil) {
		NSString* imgPath = [[NSBundle mainBundle] pathForResource:[listTitleToImageNameMap objectForKey:cell.textLabel.text] ofType:@"png"];
		cell.imageView.image = [UIImage imageWithContentsOfFile: imgPath];
    } else {
		cell.imageView.image = [UIImage imageNamed:@"tasks.png"];
	}
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	cell.backgroundColor = [UIColor colorWithRed:0 green:0.62890625 blue:0.82421875 alpha:1];
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
	[[tableView cellForRowAtIndexPath:indexPath] setSelected:NO];
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* domain = [loginDict objectForKey:@"domain"];
	NSString* login = [loginDict objectForKey:@"login"];
	NSString* password = [loginDict objectForKey:@"password"];
	NSURL* url = [NSURL URLWithString:[NSString stringWithFormat:@"%@%@", domain, @"/_vti_bin/Lists.asmx"]];
	NSString* host = [[NSURL URLWithString:domain] host];
	domain = [host stringByReplacingOccurrencesOfString:@"www." withString:@""];
	NSString* envelope = [NSString stringWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"GetListItems" ofType:@"txt"] 
												   encoding: NSUTF8StringEncoding 
													  error:nil];
	envelope = [NSString stringWithFormat:envelope, [keysArr objectAtIndex:indexPath.row], @"99999"];
	SoapRequest* soapReq = [[SoapRequest alloc] initWithUrl:url 
												   username:login 
												   password:password 
													 domain:domain 
												   delegate:self 
												   envelope:envelope action:@"http://schemas.microsoft.com/sharepoint/soap/GetListItems"];
	[soapReq startRequest];
	self.selectedRowTitle = [listsData objectForKey:[keysArr objectAtIndex:indexPath.row]];
}
- (void) requestFinishedWithXml: (CXMLDocument*) doc {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	NSLog(@"%@", [doc description]);
	NSArray* listsNodes = [doc nodesForXPath:@"/Envelope/Body/GetListItemsResponse/GetListItemsResult/listitems/data/row" error:nil];
	NSMutableDictionary* listDict = [NSMutableDictionary dictionary];
	//NSString* nameAttrName = [listTitleToUrlAttributeName objectForKey:selectedRowTitle] == nil ? @"ows_EncodedAbsUrl" : [listTitleToUrlAttributeName objectForKey:selectedRowTitle];
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* titleAttr = [listNode attributeForName:@"ows_Title"];
		CXMLNode* nameAttr = [listNode attributeForName:@"ows_EncodedAbsUrl"];
		if (nameAttr != nil && titleAttr != nil) {
			[listDict setObject:[titleAttr stringValue] forKey:[nameAttr stringValue]];
		}
	}
	SharepointListVC* slvc = [[SharepointListVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	[self.navCntrl pushViewController:slvc animated:YES];
	[slvc release];
}
- (void) requestFinishedWithError: (NSError*) error {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error description] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)dealloc {
    [super dealloc];
	[listsData release];
	[keysArr release];
	[listTitleToImageNameMap release];
	/*[listTitleToUrlAttributeName release];*/
	[selectedRowTitle release];
	[navCntrl release];
}

@end

