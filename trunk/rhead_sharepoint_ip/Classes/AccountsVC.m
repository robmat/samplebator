#import "AccountsVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "LoginVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SharepointListsVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"
#import "iToast.h"

@implementation AccountsVC

@synthesize accounts,tableView;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	self.accounts = [accountsDict allValues];
	self.title = @"Projects";
	self.tableView.backgroundColor = [UIColor colorWithRed:0.195 green:0.234 blue:0.437 alpha:1];
	[self.tableView reloadData];
	[self setUpTabBarButtons];
	backBtn.hidden = YES;
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[accounts release];
}
- (UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	AccountTVC* cell = nil;
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"AccountTVC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (AccountTVC*) currentObject;
                break;
            }
        }
    }
	cell.navigationController = self.navigationController;
	cell.delegate = self;
	cell.titleLbl.text = [[NSURL URLWithString: [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"domain"]] host];
    cell.url = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"domain"];
	return cell;
}
-(NSInteger) tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [accounts count];
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 72;
}
- (void)tableView:(UITableView *)tableView_ didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView_ cellForRowAtIndexPath:indexPath] setSelected:NO];
}
@end

@implementation AccountTVC

@synthesize titleLbl, delegate, navigationController, goBtn, url;

- (IBAction)delAction: (id) sender {
	UIActionSheet* as = [[UIActionSheet alloc] initWithTitle:@"Confirm delete" delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:@"Delete" otherButtonTitles:nil];
	[as showInView: delegate.view];
	[as release];
}
- (IBAction)goAction: (id) sender {
    goBtn.hidden = YES;
	NSMutableDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	NSDictionary* loginDict = [accountsDict objectForKey:url];
	[loginDict writeToFile:[rhead_sharepoint_ipAppDelegate loginDictPath] atomically:YES];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
	NSString* envelope = [NSString stringWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"GetListCollection" ofType:@"txt"] 
												   encoding: NSUTF8StringEncoding 
													  error: nil];
	NSString* host = [[NSURL URLWithString:[loginDict objectForKey:@"domain"]] host];
	host = [host stringByReplacingOccurrencesOfString:@"www." withString:@""];
	ASIHTTPRequest* request = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:[[loginDict objectForKey:@"domain"] stringByAppendingString:@"/_vti_bin/Lists.asmx"]]];
	[request setRequestMethod:@"POST"];
	[request setUsername:[loginDict objectForKey:@"login"]];
	[request setPassword:[loginDict objectForKey:@"password"]];
	[request setDomain:host];
	[request addRequestHeader:@"SOAPAction" value:@"http://schemas.microsoft.com/sharepoint/soap/GetListCollection"];
	[request addRequestHeader:@"Content-Type" value:@"text/xml; charset=\"UTF-8\""];
	[request setPostBody:[NSMutableData dataWithData:[envelope dataUsingEncoding:NSUTF8StringEncoding]]];
	[request addRequestHeader:@"Content-Length" value:[NSString stringWithFormat:@"%i", [envelope length]]];
	[request setDelegate:self];
	[request startAsynchronous];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
    goBtn.hidden = NO;
	NSString* responseString = [request responseString];
	responseString = [responseString stringByReplacingOccurrencesOfString:@"soap:" withString:@""];
	responseString = [responseString stringByReplacingOccurrencesOfString:@"xmlns=\"http://schemas.microsoft.com/sharepoint/soap/\"" withString:@""];
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData: [responseString dataUsingEncoding:NSUTF8StringEncoding] options: 0 error: nil];
	NSArray* listsNodes = [doc nodesForXPath:@"/Envelope/Body/GetListCollectionResponse/GetListCollectionResult/Lists/List" error:nil];
	NSMutableDictionary* listDict = [NSMutableDictionary dictionary];
	NSMutableDictionary* titletoNameDict = [NSMutableDictionary dictionary];
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* titleAttr = [listNode attributeForName:@"Title"];
		CXMLNode* nameAttr = [listNode attributeForName:@"Name"];
		[listDict setObject:[titleAttr stringValue] forKey:[nameAttr stringValue]];
		[titletoNameDict setObject:[nameAttr stringValue] forKey:[titleAttr stringValue]];
	}
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	SharepointListsVC* slvc = [[SharepointListsVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	slvc.titletoNameDict = titletoNameDict;
	[self.navigationController pushViewController:slvc animated:YES];
	[slvc release];
	//NSLog(@"%@", [doc description]);
	NSDictionary* loginDict = [NSDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate loginDictPath]];
	NSMutableDictionary* accountsDict = nil;
	if ([[NSFileManager defaultManager] fileExistsAtPath:[rhead_sharepoint_ipAppDelegate accountsPath]]) {
		accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	} else {
		accountsDict = [NSMutableDictionary dictionary];
	}
	if ([accountsDict objectForKey:[loginDict objectForKey:@"domain"]] == nil) {
		iToast* toast = [iToast makeText:@"Account addded to accounts list"];
		[toast setDuration:3000];
		[toast show];
	}
	[accountsDict setObject:loginDict forKey:[loginDict objectForKey:@"domain"]];
	[accountsDict writeToFile:[rhead_sharepoint_ipAppDelegate accountsPath] atomically:YES];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
    goBtn.hidden = NO;
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex {
	if (buttonIndex == 0) {	
		NSMutableDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
		[accountsDict removeObjectForKey:titleLbl.text];
		[accountsDict writeToFile:[rhead_sharepoint_ipAppDelegate accountsPath] atomically:YES];
		[delegate viewDidLoad];
	}
}

- (void)dealloc {
	[super dealloc];
	[titleLbl release];
	[delegate release];
	[navigationController release];
    [goBtn release];
    [url release];
}

@end
