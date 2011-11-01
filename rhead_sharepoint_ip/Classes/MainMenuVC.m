#import "MainMenuVC.h"
#import "LoginVC.h"
#import "ServicesVC.h"
#import "ContactVC.h"
#import "AppInfoVC.h"

@implementation MainMenuVC

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	self.navigationItem.titleView = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"title_image.png"]];
	self.navigationItem.hidesBackButton = YES;
	[self setUpTabBarButtons];
}
- (void)viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = NO;
}
- (IBAction)servAction:(id) sender {
	ServicesVC* svc = [[ServicesVC alloc] init];
	[self.navigationController pushViewController:svc animated:YES];
	[svc release];
}	
- (IBAction)loginAction:(id) sender {
	LoginVC* lvc = [[LoginVC alloc] init];
	[self.navigationController pushViewController:lvc animated:YES];
	[lvc release];
}
- (IBAction)contactAction:(id) sender {
	ContactVC* cvc = [[ContactVC alloc] init];
	[self.navigationController pushViewController:cvc animated:YES];
	[cvc release];
}
- (IBAction)appInfoAction:(id) sender {
	AppInfoVC* aivc = [[AppInfoVC alloc] init];
	[self.navigationController pushViewController:aivc animated:YES];
	[aivc release];
}
- (void)dealloc {
    [super dealloc];
}

@end
