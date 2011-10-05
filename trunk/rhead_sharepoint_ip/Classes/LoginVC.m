#import "LoginVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SharepointListsVC.h"
#import "CXMLNode.h"
#import "CXMLElement.h"

@implementation LoginVC

@synthesize loginTxt, passwTxt, domainTxt;

- (void)viewDidLoad {
    [super viewDidLoad];
}
- (void) loginAction:(id) sender {
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
	for (CXMLElement* listNode in listsNodes) {
		CXMLNode* titleAttr = [listNode attributeForName:@"Title"];
		CXMLNode* nameAttr = [listNode attributeForName:@"Name"];
		[listDict setObject:[titleAttr stringValue] forKey:[nameAttr stringValue]];
	}
	[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	SharepointListsVC* slvc = [[SharepointListsVC alloc] initWithNibName:nil bundle:nil];
	slvc.listsData = listDict;
	[self.navigationController pushViewController:slvc animated:YES];
	[slvc release];
	NSLog(@"%@", [doc description]);
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] description] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
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
