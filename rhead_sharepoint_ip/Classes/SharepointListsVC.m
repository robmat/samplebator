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
	[self setUpTabBarButtons];
}
- (void)viewWillAppear:(BOOL)animated {
	[super viewWillAppear:animated];
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSString* name = [loginDict objectForKey:@"name"];
	self.title = name;
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
	self.title = @"Back";
}
- (IBAction) presentationsAction: (id) sender {
	[self defaultListItemclickAction:@"Presentations"];
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
	categoryPressed = actionStr;
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
	envelope = [NSString stringWithFormat:envelope, nameStr, @"99999", @""];
	myListName = nameStr;
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
		CXMLNode* nameAttr = [listNode attributeForName:@"ows_EncodedAbsUrl"];
		CXMLNode* dateCreatedAttr = [listNode attributeForName:@"ows_Created"];
		CXMLNode* dateModifiedAttr = [listNode attributeForName:@"ows_Modified"];
		CXMLNode* titleAttr = [listNode attributeForName:@"ows_Title"];
		CXMLNode* contentAttr = [listNode attributeForName:@"ows_ContentType"];
		if (titleAttr == nil) {
			titleAttr = [listNode attributeForName:@"ows_LinkFilename"];
		}
		NSMutableDictionary* dict = [NSMutableDictionary dictionary];
		if ([titleAttr stringValue]) {
			[dict setObject:[titleAttr stringValue] forKey:@"ows_Title"];
		}
		if ([nameAttr stringValue]) {
			[dict setObject:[nameAttr stringValue] forKey:@"ows_EncodedAbsUrl"];
		}
		if ([dateCreatedAttr stringValue]) {
			[dict setObject:[dateCreatedAttr stringValue] forKey:@"ows_Created"];
		}
		if ([dateModifiedAttr stringValue]) {
			[dict setObject:[dateModifiedAttr stringValue] forKey:@"ows_Modified"];
		}
		if ([contentAttr stringValue]) {
			[dict setObject:[contentAttr stringValue] forKey:@"ows_ContentType"];
		}
		[listDict setObject:dict forKey:[nameAttr stringValue]];
	}
	SharepointListVC* slvc = [[SharepointListVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	slvc.title = categoryPressed;
	slvc.myListName = myListName;
	slvc.currentFolder = [NSString stringWithFormat:@"%@/", categoryPressed];
	[self.navigationController pushViewController:slvc animated:YES];
	[slvc release];
}
- (void) requestFinishedWithError: (NSError*) error {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[error localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	//[tableVC release];
	[listsData release];
	[titletoNameDict release];
	[categoryPressed release];
	[myListName release];
}

@end
