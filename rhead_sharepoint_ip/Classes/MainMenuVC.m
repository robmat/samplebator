#import "MainMenuVC.h"
#import "LoginVC.h"
#import "ServicesVC.h"
#import "ContactVC.h"
#import "AppInfoVC.h"
#import "SoapRequest.h"
#import "Base64.h"

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
- (IBAction)test:(id)sender {
    NSString* path = [[NSBundle mainBundle] pathForResource:@"uploadImage" ofType:@"txt"];
    NSString* envelope = [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
       
    path = [[NSBundle mainBundle] pathForResource:@"addaccount" ofType:@"png"];
    NSData* imgData = [NSData dataWithContentsOfFile:path];
    //NSString* imgStr = [[NSString alloc] initWithData:imgData encoding:NSASCIIStringEncoding];
    [Base64 initialize];
    NSString* base64ImgStr = [Base64 encode:imgData];
    NSLog(@"%@", base64ImgStr);
    envelope = [NSString stringWithFormat:envelope, @"Pictures", @"", base64ImgStr, @"test.png"];
    
    NSURL* url = [NSURL URLWithString:@"https://www.rheadsharepoint.com/tps/_vti_bin/imaging.asmx"];
    SoapRequest* soap = [[SoapRequest alloc] initWithUrl:url username:@"myoxygen" password:@"rhead150811" domain:@"https://www.rheadsharepoint.com/tps/" delegate:self envelope:envelope action:@"http://schemas.microsoft.com/sharepoint/soap/ois/Upload"];
    [soap startRequest];
}
- (void) requestFinishedWithXml: (CXMLDocument*) doc {
    NSLog(@"%@", [doc description]);
}
- (void) requestFinishedWithError: (NSError*) error {
    NSLog(@"%@", [error description]);
}
- (void)dealloc {
    [super dealloc];
}

@end
