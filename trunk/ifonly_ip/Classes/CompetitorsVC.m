#import "CompetitorsVC.h"
#import "WebViewController.h"

@implementation CompetitorsVC

@synthesize webView;

- (void)viewDidLoad {
    [super viewDidLoad];
	self.title = @"Competition";
	NSString* path = [[NSBundle mainBundle] pathForResource:@"competitors" ofType:@"html"];
	NSString* html = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
	[webView loadHTMLString:html baseURL:[NSURL URLWithString: @"http://foo.bar/"]];
	webView.delegate = self;
}
- (BOOL)webView:(UIWebView*)webView shouldStartLoadWithRequest:(NSURLRequest*)request navigationType:(UIWebViewNavigationType)navigationType {
	NSLog(@"%@", request.URL.absoluteString);
	if (![request.URL.absoluteString isEqualToString:@"http://foo.bar/"]) {
		WebViewController* wvc = [[WebViewController alloc] initWithNibName:nil bundle:nil];
		wvc.url = request.URL.absoluteString;
		[self.navigationController pushViewController:wvc animated:YES];
		[wvc release];
		return NO;
	}
	return YES;
}
- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
	backBtn.hidden = YES;
}
- (void)dealloc {
    [super dealloc];
}

@end
