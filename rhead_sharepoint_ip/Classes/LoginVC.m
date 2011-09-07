#import "LoginVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"
#import "ASIHTTPRequest.h"

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
		[request startSynchronous];
		NSLog(@"Response: %@", [[request responseData] description]);
		NSLog(@"Error: %@", [[request error] description]);
		[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;
	}
}
- (void)dealloc {
	[passwTxt release];
	[domainTxt release];
	[loginTxt release];
    [super dealloc];
}


@end
