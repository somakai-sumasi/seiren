{
  description = "Seiren - Code quality improvement prompts via MCP server";

  inputs.nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";

  outputs = { nixpkgs, ... }:
    let
      systems = [ "x86_64-linux" "aarch64-linux" "x86_64-darwin" "aarch64-darwin" ];
      forAllSystems = f: nixpkgs.lib.genAttrs systems (system: f nixpkgs.legacyPackages.${system});
    in {
      packages = forAllSystems (pkgs: rec {
        seiren = pkgs.buildGoModule {
          pname = "seiren";
          version = "0.1.0";

          src = ./.;

          vendorHash = "sha256-kHSRuP58Tkt23+b2rpQFf8aLUbeuMtkzHfwyrEyPPek=";

          subPackages = [ "cmd/cli" "cmd/mcp" ];

          ldflags = [ "-s" "-w" ];

          postInstall = ''
            mv $out/bin/cli $out/bin/seiren
            mv $out/bin/mcp $out/bin/seiren-mcp
          '';

          meta = with pkgs.lib; {
            description = "Code quality improvement prompts via MCP server";
            homepage = "https://github.com/somakai-sumasi/seiren";
            mainProgram = "seiren-mcp";
          };
        };
        default = seiren;
      });

      devShells = forAllSystems (pkgs: {
        default = pkgs.mkShellNoCC {
          buildInputs = with pkgs; [
            go
            gopls
          ];
        };
      });
    };
}
