#import "AccountsVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "LoginVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SharepointListsVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"
#import "iToast.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation AccountsVC

@synthesize accounts, tableView, blankBottomBar;

- (void)viewDidLoad {
    [super viewDidLoad];
	NSDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
	self.accounts = [accountsDict allValues];
	self.title = @"Projects";
	self.tableView.backgroundColor = [UIColor clearColor];//[UIColor colorWithRed:0.195 green:0.234 blue:0.437 alpha:1];
	[self.tableView reloadData];
	[self setUpTabBarButtons];
	backBtn.hidden = YES;
    self.navigationItem.leftBarButtonItem = [[UIBarButtonItem alloc] initWithTitle:@"Edit" style:UIBarButtonItemStyleBordered target:self action:@selector(editAction:)];
    self.navigationItem.leftBarButtonItem.enabled = NO;
    self.navigationItem.rightBarButtonItem = [[UIBarButtonItem alloc] initWithImage:[UIImage imageNamed:@"add_project_button.png"] style:UIBarButtonItemStyleBordered target:self action:@selector(addAction:)];
}
- (void)addAction: (id) sender {
    LoginVC* lvc = [[LoginVC alloc] init];
    [self.navigationController pushViewController:lvc animated:YES];
    [lvc release];
}
- (void)editAction: (id) sender {
    NSIndexPath* path = [self.tableView indexPathForSelectedRow];
    LoginVC* lvc = [[LoginVC alloc] init];
    [self.navigationController pushViewController:lvc animated:YES];
    NSDictionary* accountsDict = [NSMutableDictionary dictionaryWithContentsOfFile:[rhead_sharepoint_ipAppDelegate accountsPath]];
    NSDictionary* account = [accountsDict objectForKey:[[accounts objectAtIndex:path.row] objectForKey:@"domain"]];
    lvc.titleTxt.text = [account objectForKey:@"name"];
    lvc.domainTxt.text = [account objectForKey:@"domain"];
    lvc.loginTxt.text = [account objectForKey:@"login"];
    lvc.passwTxt.text = [account objectForKey:@"password"];
    [lvc release];
}
- (void)viewWillDisappear:(BOOL)animated {
	[super viewWillDisappear:animated];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self setUpViewByOrientation: self.interfaceOrientation];
}
- (UITableViewCell*)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	/*
    AccountTVC* cell = nil;
    BOOL isPortrait = UIDeviceOrientationIsPortrait(self.interfaceOrientation);
    
    NSString* nibName = isPortrait ? @"AccountTVC" : @"AccountTVCLand";
    
	if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:nibName owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (AccountTVC*) currentObject;
                break;
            }
        }
    }
    */
    AccountTVC* cell = [[[AccountTVC alloc] init] autorelease];
    cell.accessoryType = UITableViewCellAccessoryDetailDisclosureButton;
	cell.navigationController = self.navigationController;
	cell.delegate = self;
	cell.titleLbl.text = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"name"];
    cell.textLabel.text = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"name"];
    cell.url = [[self.accounts objectAtIndex:indexPath.row] objectForKey:@"domain"];
	return cell;
}
-(NSInteger) tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [accounts count];
}
- (CGFloat) tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 42;
}
- (void)tableView:(UITableView *)tableView_ didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	//[[tableView_ cellForRowAtIndexPath:indexPath] setSelected:NO];
    for (int i = 0; i < [accounts count]; i++) {
        AccountTVC* cell = (AccountTVC*) [tableView_ cellForRowAtIndexPath:[NSIndexPath indexPathForRow:i  inSection:0]];
        cell.imageView.image = nil;
        [cell.delBtn removeFromSuperview];
    }
    AccountTVC* cell = (AccountTVC*) [tableView_ cellForRowAtIndexPath:indexPath];
    cell.imageView.image = [UIImage imageNamed:@"delete_project_button.png"];
    UIButton* btn = [UIButton buttonWithType:UIButtonTypeCustom];
    btn.frame = CGRectMake(0, 0, 62, 42);
    [cell.contentView addSubview:btn];
    [btn addTarget:cell action:@selector(delAction:) forControlEvents:UIControlEventTouchUpInside];
    [cell setDelBtn:btn];
    self.navigationItem.leftBarButtonItem.enabled = YES;
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
    return YES;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    [self setUpViewByOrientation: toInterfaceOrientation];
}
- (void)setUpViewByOrientation: (UIInterfaceOrientation)toInterfaceOrientation {
    if (toInterfaceOrientation==UIInterfaceOrientationPortrait || toInterfaceOrientation==UIInterfaceOrientationPortraitUpsideDown) {
        self.blankBottomBar.hidden = YES;
        infoBtn.frame = CGRectMake(30, 376, 45, 37);
        newsBtn.frame = CGRectMake(243, 376, 45, 37);
        contactBtn.frame = CGRectMake(98, 376, 45, 37);
    } else {
        self.blankBottomBar.hidden = NO;
        infoBtn.frame = CGRectMake(108, 227, 45, 37);
        newsBtn.frame = CGRectMake(321, 227, 45, 37);
        contactBtn.frame = CGRectMake(176, 227, 45, 37);
    }
    [tableView reloadData];
}
- (void)tableView:(UITableView *)tableView_ accessoryButtonTappedForRowWithIndexPath:(NSIndexPath *)indexPath {
    AccountTVC* cell = (AccountTVC*) [tableView_ cellForRowAtIndexPath:indexPath];
    [cell goAction: nil];
}
- (void)dealloc {
    [super dealloc];
	[tableView release];
	[accounts release];
    [blankBottomBar release];
}
@end

@implementation AccountTVC

@synthesize titleLbl, delegate, navigationController, goBtn, url, delBtn;

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
	[rhead_sharepoint_ipAppDelegate hideTabcontroller:slvc];
    //[self.navigationController pushViewController:slvc animated:YES];
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
		[accountsDict removeObjectForKey:self.url];
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
