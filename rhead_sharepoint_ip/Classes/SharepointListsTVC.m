#import "SharepointListsTVC.h"
#import "SoapRequest.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "SharepointListVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"

@implementation SharepointListsTVC

@synthesize listsData, keysArr, selectedRowTitle, navCntrl;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.keysArr = [listsData allKeys];
	listTitleToImageNameMap = [[NSMutableDictionary alloc] init];
	[listTitleToImageNameMap setObject:@"calendar" forKey:@"Calendar"];
	[listTitleToImageNameMap setObject:@"project_reports" forKey:@"Project Reports"];
	[listTitleToImageNameMap setObject:@"tasks" forKey:@"Tasks"];
	[listTitleToImageNameMap setObject:@"site_photos" forKey:@"Site Progress Photos"];
	[listTitleToImageNameMap setObject:@"team_discusion" forKey:@"Team Discussion"];
	[listTitleToImageNameMap setObject:@"presentations" forKey:@"Presentations"];
	[listTitleToImageNameMap setObject:@"project_documents" forKey:@"Project Documents"];
	[listTitleToImageNameMap setObject:@"shared_documents" forKey:@"Shared Documents"];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [listsData count];
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 50;
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
	cell.imageView.image = [rhead_sharepoint_ipAppDelegate imageWithImage:cell.imageView.image scaledToSize:CGSizeMake(44, 44)];
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	cell.backgroundColor = [UIColor colorWithRed:0 green:0.62890625 blue:0.82421875 alpha:1];
	cell.textLabel.font = [UIFont fontWithName:@"Helvetica" size:14];
	cell.textLabel.textColor = [UIColor whiteColor];
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
	NSMutableDictionary* dateDict = [NSMutableDictionary dictionary];
	//NSString* nameAttrName = [listTitleToUrlAttributeName objectForKey:selectedRowTitle] == nil ? @"ows_EncodedAbsUrl" : [listTitleToUrlAttributeName objectForKey:selectedRowTitle];
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* titleAttr = [listNode attributeForName:@"ows_Title"];
		CXMLNode* nameAttr = [listNode attributeForName:@"ows_EncodedAbsUrl"];
		CXMLNode* dateAttr = [listNode attributeForName:@"ows_Created"];
		if (nameAttr != nil && titleAttr != nil) {
			[listDict setObject:[titleAttr stringValue] forKey:[nameAttr stringValue]];
		}
		if (nameAttr != nil && dateAttr != nil) {
			[dateDict setObject:[dateAttr stringValue] forKey:[nameAttr stringValue]];
		}
	}
	SharepointListVC* slvc = [[SharepointListVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	slvc.currentFolder = [NSString stringWithFormat:@"/%@/", selectedRowTitle];
	[self.navCntrl pushViewController:slvc animated:YES];
	[slvc release];
}
- (void) requestFinishedWithError: (NSError*) error {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
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
	[selectedRowTitle release];
	[navCntrl release];
}

@end

