#import "LoginVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SharepointListsVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"
#import "iToast.h"
#import "AccountsVC.h"

@implementation LoginVC

@synthesize loginTxt, passwTxt, domainTxt;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationItem.titleView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"title_image.png"]];
	backBtn.hidden = YES;
	[self setUpTabBarButtons];
}
- (IBAction) accountsAction: (id) sender {
	AccountsVC* avc = [[AccountsVC alloc] init];
	[self.navigationController pushViewController:avc animated:YES];
	[avc release];
}
- (void)textFieldDidBeginEditing:(UITextField *)textField {
	[self animateView:self.view up:YES distance:70];
}
- (void)textFieldDidEndEditing:(UITextField *)textField{
	[self animateView:self.view up:NO distance:70];
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField {
    [textField resignFirstResponder];
    return YES;
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
	self.navigationController.navigationBarHidden = NO;
}
- (void)viewWillDisappear:(BOOL)animated {
    [super viewWillDisappear:animated];
    [loginTxt resignFirstResponder];
    [passwTxt resignFirstResponder];
    [domainTxt resignFirstResponder];
}
- (IBAction) loginAction:(id) sender {
	if ([loginTxt.text length] == 0 || [passwTxt.text length] == 0 || [domainTxt.text length] == 0) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" 
														message:@"Please provide login, password and domain." 
													   delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		NSDictionary* loginDict = [NSDictionary dictionaryWithObjectsAndKeys:loginTxt.text, @"login", passwTxt.text, @"password", domainTxt.text, @"domain", nil];
		[loginDict writeToFile:[rhead_sharepoint_ipAppDelegate loginDictPath] atomically:YES];
		[UIApplication sharedApplication].networkActivityIndicatorVisible = YES;
		NSString* envelope = [NSString stringWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"GetListCollection" ofType:@"txt"] 
													   encoding: NSUTF8StringEncoding 
														  error: nil];
		NSString* host = [[NSURL URLWithString:domainTxt.text] host];
		host = [host stringByReplacingOccurrencesOfString:@"www." withString:@""];
		ASIHTTPRequest* request = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:[domainTxt.text stringByAppendingString:@"/_vti_bin/Lists.asmx"]]];
		[request setRequestMethod:@"POST"];
		[request setUsername:loginTxt.text];
		[request setPassword:passwTxt.text];
		[request setDomain:host];
		[request addRequestHeader:@"SOAPAction" value:@"http://schemas.microsoft.com/sharepoint/soap/GetListCollection"];
		[request addRequestHeader:@"Content-Type" value:@"text/xml; charset=\"UTF-8\""];
		[request setPostBody:[NSMutableData dataWithData:[envelope dataUsingEncoding:NSUTF8StringEncoding]]];
		[request addRequestHeader:@"Content-Length" value:[NSString stringWithFormat:@"%i", [envelope length]]];
		[request setDelegate:self];
		[request startAsynchronous];
	}
}
- (void)requestFinished:(ASIHTTPRequest *)request {
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
    NSString* msg = [[request error] localizedDescription];
    if ([msg rangeOfString:@"Authentication needed"].location != NSNotFound) {
        msg = @"Login and password you've provided are no recognized, check again.";
    }
    if ([msg rangeOfString:@"Authentication needed"].location != NSNotFound) {
        msg = @"Application can't find the domain you've entered, check again.";
    }
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:msg delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
}
- (void)dealloc {
	[passwTxt release];
	[domainTxt release];
	[loginTxt release];
    [super dealloc];
}


@end
