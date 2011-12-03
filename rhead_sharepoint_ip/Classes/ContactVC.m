#import "ContactVC.h"
#import "WebViewVC.h"
#import <MessageUI/MessageUI.h>

@implementation ContactVC

@synthesize webView;

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	self.title = @"Contact";
	[self setUpTabBarButtons];
    contactBtn.hidden = YES;
	NSString* html = [[NSBundle mainBundle] pathForResource:@"contact" ofType:@"html"];
	html = [NSString stringWithContentsOfFile:html encoding:NSUTF8StringEncoding error:nil];
	[self.webView loadHTMLString:html baseURL:[NSURL URLWithString:@"http://fake.com"]];
}
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
	NSLog(@"%@", [[request URL] absoluteString] );
	if ([[[request URL] absoluteString] isEqualToString:@"http://fake.com/"]) {
		return YES;
	}
	if ([[[request URL] absoluteString] rangeOfString:@"@rheadgroup.com"].location != NSNotFound) {
		MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
		if ([MFMailComposeViewController canSendMail] && controller != nil)	{
			controller.mailComposeDelegate = self;
			NSString* receiver = [[request URL] absoluteString];
			receiver = [receiver stringByReplacingOccurrencesOfString:@"http://fake.com/" withString:@""];
			[controller setToRecipients:[NSArray arrayWithObjects:receiver, nil]];
			[controller setSubject:@""];
			[controller setMessageBody:@"" isHTML:NO]; 
			[self presentModalViewController:controller animated:YES];
			[controller release];
			return NO;
		} else {
			UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
															message:@"Can't send mail, probably no email account is set up." 
														   delegate:nil 
												  cancelButtonTitle:@"Ok" 
												  otherButtonTitles:nil];
			[alert show];
			[alert release];
			return NO;
		}
	}
	WebViewVC* wvvc = [[WebViewVC alloc] init];
	wvvc.url = [[request URL] absoluteString];
	[self.navigationController pushViewController:wvvc animated:YES];
	[wvvc release];
	return YES;
}
- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	NSString* html = [[NSBundle mainBundle] pathForResource:@"contact" ofType:@"html"];
	html = [NSString stringWithContentsOfFile:html encoding:NSUTF8StringEncoding error:nil];
	[self.webView loadHTMLString:html baseURL:[NSURL URLWithString:@"http://fake.com"]];
	if (result == MFMailComposeResultCancelled) {
		[self dismissModalViewControllerAnimated:YES];
		return;
	}
	if (result == MFMailComposeResultSent) {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Mail sent." 
														message:@"Sending the mail succeeded." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[self dismissModalViewControllerAnimated:YES];
}
- (void)dealloc {
    [super dealloc];
	[webView release];
}

@end
