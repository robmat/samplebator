#import "SharepointListsVC.h"
#import "SharepointListsTVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "SharepointListVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"

@implementation SharepointListsVC

@synthesize tableView, tableVC, listsData, titletoNameDict;

- (void)viewDidLoad {
    [super viewDidLoad];
	tableVC = [[SharepointListsTVC alloc] initWithStyle:UITableViewStylePlain];
	tableVC.listsData = listsData;
	tableVC.tableView = tableView;
	tableVC.navCntrl = self.navigationController;
	[tableVC viewDidLoad];
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* domain = [loginDict objectForKey:@"domain"];
	domain = [domain stringByReplacingOccurrencesOfString:@"https://" withString:@""];
	domain = [domain stringByReplacingOccurrencesOfString:@"http://" withString:@""];
	domain = [domain stringByReplacingOccurrencesOfString:@"www." withString:@""];
	self.title = domain;
}
- (IBAction) presentationsAction: (id) sender {
	[self defaultListItemclickAction:@"Presetation"];
}
- (IBAction) progressAction: (id) sender {
	[self defaultListItemclickAction:@"Site Progress Photos"];
}
- (IBAction) reportsAction: (id) sender {
	[self defaultListItemclickAction:@"Project Reports"];
}
- (IBAction) documentsAction: (id) sender {
	[self defaultListItemclickAction:@"Project Documents"];
}
- (void) defaultListItemclickAction: (NSString*) actionStr {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
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
	NSString* nameStr = [titletoNameDict objectForKey:actionStr];
	envelope = [NSString stringWithFormat:envelope, nameStr, @"99999"];
	SoapRequest* soapReq = [[SoapRequest alloc] initWithUrl:url 
												   username:login 
												   password:password 
													 domain:domain 
												   delegate:self 
												   envelope:envelope action:@"http://schemas.microsoft.com/sharepoint/soap/GetListItems"];
	[soapReq startRequest];
}
- (void) requestFinishedWithXml: (CXMLDocument*) doc {
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	NSLog(@"%@", [doc description]);
	NSArray* listsNodes = [doc nodesForXPath:@"/Envelope/Body/GetListItemsResponse/GetListItemsResult/listitems/data/row" error:nil];
	NSMutableDictionary* listDict = [NSMutableDictionary dictionary];
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* titleAttr = [listNode attributeForName:@"ows_Title"];
		CXMLNode* nameAttr = [listNode attributeForName:@"ows_EncodedAbsUrl"];
		if (nameAttr != nil && titleAttr != nil) {
			[listDict setObject:[titleAttr stringValue] forKey:[nameAttr stringValue]];
		}
	}
	SharepointListVC* slvc = [[SharepointListVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	[self.navigationController pushViewController:slvc animated:YES];
	[slvc release];
}
- (void) requestFinishedWithError: (NSError*) error {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error description] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[tableVC release];
	[listsData release];
	[titletoNameDict release];
}

@end
