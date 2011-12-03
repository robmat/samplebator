#import "AppInfoVC.h"
#import "WebViewVC.h"

@implementation AppInfoVC

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
	self.navigationItem.titleView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"title_image.png"]];
	[self setUpTabBarButtons];
    infoBtn.hidden = YES;
}
- (IBAction)visitAction: (id) sender {
	WebViewVC* wwvc = [[WebViewVC alloc] init];
	wwvc.url = @"http://www.rheadgroup.com";
	wwvc->dontAppendPass = YES;
	[self.navigationController pushViewController:wwvc animated:YES];
	[wwvc release];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [[UIDevice currentDevice] setOrientation:UIInterfaceOrientationPortrait];
}
- (void)dealloc {
    [super dealloc];
}

@end
