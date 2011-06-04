#import "WebViewController.h"


@implementation WebViewController

@synthesize webView, url;

- (void)viewDidLoad {
    [super viewDidLoad];
	[webView loadRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:url]]];
	self.title = @"Web View";
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
}

@end
