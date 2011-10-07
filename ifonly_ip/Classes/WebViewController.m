#import "WebViewController.h"

@implementation WebViewController

@synthesize webView, url;

- (void)webViewDidStartLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = YES;
}
- (void)webViewDidFinishLoad:(UIWebView *)webView {
	UIApplication* application = [UIApplication sharedApplication];
	application.networkActivityIndicatorVisible = NO;
}
- (void)viewDidLoad {
    [super viewDidLoad];
	webView.delegate = self;
	[webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:url]]];
	self.title = @"Web page";
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
	return YES;
}
- (void)dealloc {
	[webView release];
    [super dealloc];
}

@end
